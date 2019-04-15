<?php
declare(strict_types = 1);

namespace app\modules\history\models;

use app\helpers\ArrayHelper;
use app\helpers\Icons;
use app\models\core\ActiveRecordExtended;
use app\models\core\helpers\ReflectionHelper;
use app\models\core\LCQuery;
use app\modules\users\models\Users;
use ReflectionException;
use Throwable;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\base\UnknownClassException;

/**
 * Модель истории изменений объекта (предполагается, что это ActiveRecord, но по факту это любая модель с атрибутами)
 *
 * @property ActiveRecordLoggerInterface $loggerModel AR-интерфейс для работы с базой логов
 */
class ModelHistory extends Model {
	public $loggerModel;

	private $requestModel;
	private $modelHistoryRules = [];

	/**
	 * @param string $className
	 * @param int $modelKey
	 * @return ActiveRecordLoggerInterface[]
	 * @throws InvalidConfigException
	 * @throws ReflectionException
	 * @throws Throwable
	 * @throws UnknownClassException
	 */
	public function getHistory(string $className, int $modelKey):array {
		$this->loggerModel = $this->loggerModel??ActiveRecordLogger::class;

		try {
			$askedClass = ReflectionHelper::LoadClassByName(self::ExpandClassName($className));//Пытаемся загрузить класс приложения
			$this->requestModel = $askedClass::findModel($modelKey);
		} /** @noinspection BadExceptionsProcessingInspection */ catch (ReflectionException $t) {//не получилось загрузить, грузим as is
			return $this->loggerModel::find()->where(['model' => $className, 'model_key' => $modelKey])->orderBy('at')->all();
		}

		$this->modelHistoryRules = $this->requestModel->hasMethod('historyRules')?$this->requestModel->historyRules():[];

		/** @var LCQuery $findCondition */
		$findCondition = $this->loggerModel::find()->where(['model' => $this->requestModel->formName(), 'model_key' => $modelKey]);//поиск по изменениям в основной таблице модели
		/** @var array $relationsRules */
		$relationsRules = ArrayHelper::getValue($this->modelHistoryRules, 'relations', []);
		foreach ($relationsRules as $relatedModelClassName => $relationRule) {/*Разбираем правила релейшенов в истории, собираем правила поиска по изменениям в связанных таблицах*/
			$relatedModel = ReflectionHelper::LoadClassByName($relatedModelClassName);
			if (is_callable($relationRule)) {
				$relationRule($findCondition, $relatedModel);
			} elseif (is_array($relationRule)) {
				$linkKey = ArrayHelper::key($relationRule);
				$linkValue = $relationRule[$linkKey];
				$modelKey = $this->requestModel->$linkKey;
				$findCondition->orWhere("model = '{$relatedModel->formName()}' and (new_attributes->'$.{$linkValue}' = {$modelKey} or old_attributes->'$.{$linkValue}' = {$modelKey})");
			} else throw new InvalidConfigException('Relation rule must be array or callable instance!');
		}
		return $findCondition->orderBy('at')->all();
	}

	/**
	 * Вытаскивает из записи описание изменений атрибутов, конвертируя их в набор HistoryEventAction
	 * @param ActiveRecordLoggerInterface $record
	 * @return HistoryEventAction[]
	 * @throws Throwable
	 */
	private function getEventActions(ActiveRecordLoggerInterface $record):array {
		$diff = [];
		$labels = (null === $modelClass = ReflectionHelper::LoadClassByName(self::ExpandClassName($record->model)))?[]:$modelClass->attributeLabels();

		foreach ($record->old_attributes as $attributeName => $attributeValue) {
			if (isset($record->new_attributes[$attributeName])) {
				$diff[] = new HistoryEventAction([
					'attributeName' => ArrayHelper::getValue($labels, $attributeName, $attributeName),
					'attributeOldValue' => $this->SubstituteAttributeValue($attributeName, $attributeValue, $record->model),
					'type' => HistoryEventAction::ATTRIBUTE_CHANGED,
					'attributeNewValue' => $this->SubstituteAttributeValue($attributeName, $record->new_attributes[$attributeName], $record->model)
				]);
			} else {
				$diff[] = new HistoryEventAction([
					'attributeName' => ArrayHelper::getValue($labels, $attributeName, $attributeName),
					'attributeOldValue' => $this->SubstituteAttributeValue($attributeName, $attributeValue, $record->model),
					'type' => HistoryEventAction::ATTRIBUTE_DELETED
				]);

			}
		}
		$e = array_diff_key($record->new_attributes, $record->old_attributes);

		foreach ($e as $attributeName => $attributeValue) {
			if (!isset($record->old_attributes[$attributeName]) || null === ArrayHelper::getValue($record->old_attributes, $attributeName)) {
				$diff[] = new HistoryEventAction([
					'attributeName' => ArrayHelper::getValue($labels, $attributeName, $attributeName),
					'attributeNewValue' => $this->SubstituteAttributeValue($attributeName, $attributeValue, $record->model),
					'type' => HistoryEventAction::ATTRIBUTE_CREATED
				]);
			}
		}

		return $diff;
	}

	/**
	 * Вытаскивает из записи описание изменений атрибутов, конвертируя их в набор HistoryEventAction для тех случаев, когда не удалось определить сопоставление логированного имени класса и кодовой модель
	 * @param ActiveRecordLoggerInterface $record
	 * @return array
	 * @throws Throwable
	 */
	private function getEventActionsDegraded(ActiveRecordLoggerInterface $record):array {
		$diff = [];
		foreach ($record->old_attributes as $attributeName => $attributeValue) {
			if (isset($record->new_attributes[$attributeName])) {
				$diff[] = new HistoryEventAction([
					'attributeName' => $attributeName,
					'attributeOldValue' => $attributeValue,
					'type' => HistoryEventAction::ATTRIBUTE_CHANGED,
					'attributeNewValue' => $record->new_attributes[$attributeName]
				]);
			} else {
				$diff[] = new HistoryEventAction([
					'attributeName' => $attributeName,
					'attributeOldValue' => $attributeValue,
					'type' => HistoryEventAction::ATTRIBUTE_DELETED
				]);

			}
		}
		$e = array_diff_key($record->new_attributes, $record->old_attributes);

		foreach ($e as $attributeName => $attributeValue) {
			if (!isset($record->old_attributes[$attributeName]) || null === ArrayHelper::getValue($record->old_attributes, $attributeName)) {
				$diff[] = new HistoryEventAction([
					'attributeName' => $attributeName,
					'attributeNewValue' => $attributeValue,
					'type' => HistoryEventAction::ATTRIBUTE_CREATED
				]);
			}
		}

		return $diff;
	}

	/**
	 * @param string $attributeName Название атрибута, для которого пытаемся найти подстановку
	 * @param mixed $attributeValue Значение атрибута, которому ищем соответствие
	 * @param string $substitutionClassName Имя AR-класса, по записям которого будем искать соответствие
	 * @return mixed Подстановленное значение (если найдено, иначе переданное значение)
	 * @throws InvalidConfigException
	 * @throws ReflectionException
	 * @throws Throwable
	 * @throws UnknownClassException
	 */
	private function SubstituteAttributeValue(string $attributeName, $attributeValue, string $substitutionClassName) {
		$relationModel = ReflectionHelper::LoadClassByName(self::ExpandClassName($substitutionClassName));

		if (null === $attributeConfig = ArrayHelper::getValue($relationModel->historyRules(), "attributes.{$attributeName}")) return $attributeValue;
		if (false === $attributeConfig) return false;//не показывать атрибут
		if (is_callable($attributeConfig)) {
			return $attributeConfig($attributeName, $attributeValue);
		}
		if (is_array($attributeConfig)) {//[className => valueAttribute]
			$fromModelName = ArrayHelper::key($attributeConfig);
			/** @var ActiveRecordExtended $fromModel */
			$fromModel = ReflectionHelper::LoadClassByName($fromModelName);
			$modelValueAttribute = $attributeConfig[$fromModelName];
			return ArrayHelper::getValue($fromModel::findModel($attributeValue), $modelValueAttribute, $attributeValue);
		} else return $attributeConfig;//Можем вернуть прямо заданное значение
	}

	/**
	 * Переводит запись из лога в событие истории
	 * @param ActiveRecordLoggerInterface $logRecord
	 * @return HistoryEventInterface
	 * @throws Throwable
	 */
	public function getHistoryEvent(ActiveRecordLoggerInterface $logRecord):HistoryEventInterface {
		$result = new HistoryEvent();

		if ([] === $logRecord->old_attributes) {
			$result->eventType = HistoryEvent::EVENT_CREATED;
		} elseif ([] === $logRecord->new_attributes) {
			$result->eventType = HistoryEvent::EVENT_DELETED;
		} else {
			$result->eventType = HistoryEvent::EVENT_CHANGED;
		}

		$result->eventTime = $logRecord->timestamp;
		$result->objectName = $logRecord->model;
		$result->subject = Users::findModel($logRecord->user);
		$result->eventIcon = Icons::event_icon($result->eventType);

		if (null === $this->requestModel) {//degraded mode
			$result->actions = $this->getEventActionsDegraded($logRecord);
		} else {
			$logRecordedModel = ReflectionHelper::LoadClassByName(self::ExpandClassName($logRecord->model));
			if (null !== $labelsConfig = ArrayHelper::getValue($logRecordedModel->historyRules(), "eventConfig.eventLabels")) {
				if (is_callable($labelsConfig)) {
					$result->eventCaption = $labelsConfig($result->eventType, $result->eventTypeName);
				} elseif (is_array($labelsConfig)) {
					$result->eventCaption = ArrayHelper::getValue($labelsConfig, $result->eventType, $result->eventTypeName);
				} else $result->eventCaption = $labelsConfig;
			}

			$result->actionsFormatter = ArrayHelper::getValue($logRecordedModel->historyRules(), "eventConfig.actionsFormatter");
			$result->actions = $this->getEventActions($logRecord);
		}

		return $result;
	}

	/**
	 * Переводит набор записей из лога в набор событий
	 * @param ActiveRecordLoggerInterface[] $timeline
	 * @return HistoryEventInterface[]
	 * @throws Throwable
	 */
	public function populateTimeline(array $timeline):array {
		$result = [];
		foreach ($timeline as $record) {
			$result[] = $this->getHistoryEvent($record);
		}
		return $result;
	}

	/**
	 * @param string $shortClassName
	 * @return string
	 * @throws Throwable
	 */
	public static function ExpandClassName(string $shortClassName):string {
		return ArrayHelper::getValue(Yii::$app->modules, "history.params.classNamesMap.$shortClassName", $shortClassName);
	}

}