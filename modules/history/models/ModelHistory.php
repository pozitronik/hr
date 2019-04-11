<?php
declare(strict_types = 1);

namespace app\modules\history\models;

use app\helpers\ArrayHelper;
use app\helpers\Icons;
use app\models\core\ActiveRecordExtended;
use app\models\core\ActiveRecordLogger;
use app\models\core\ActiveRecordLoggerInterface;
use app\models\core\LCQuery;
use app\models\core\Magic;
use app\modules\users\models\Users;
use ReflectionException;
use Throwable;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\base\UnknownClassException;
use yii\db\ActiveRecord;

/**
 * Модель истории изменений объекта (предполагается, что это ActiveRecord, но по факту это любая модель с атрибутами)
 *
 * @property ActiveRecordLoggerInterface $loggerModel AR-интерфейс для работы с базой логов
 * @property ActiveRecord|ActiveRecordExtended $requestModel Модель, для которой запрашиваем историю
 */
class ModelHistory extends Model {
	public $loggerModel;
	public $requestModel;
	private $modelHistoryRules = [];

	/**
	 * @return ActiveRecordLoggerInterface[]
	 * @throws InvalidConfigException
	 * @throws Throwable
	 * @throws ReflectionException
	 * @throws UnknownClassException
	 */
	public function getHistory():array {
		$this->loggerModel = $this->loggerModel??ActiveRecordLogger::class;
		$modelKey = $this->requestModel->primaryKey;
		$this->modelHistoryRules = $this->requestModel->hasMethod('historyRules')?$this->requestModel->historyRules():[];

		/** @var LCQuery $findCondition */
		$findCondition = $this->loggerModel::find()->where(['model' => $this->requestModel->formName(), 'model_key' => $modelKey]);//поиск по изменениям в основной таблице модели
		/** @var array $relationsRules */
		$relationsRules = ArrayHelper::getValue($this->modelHistoryRules, 'relations', []);
		foreach ($relationsRules as $relatedModelClassName => $relationRule) {/*Разбираем правила релейшенов в истории, собираем правила поиска по изменениям в связанных таблицах*/
			$relatedModel = Magic::LoadClassByName($relatedModelClassName);
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
		$labels = (null === $record->modelClass)?[]:$record->modelClass->attributeLabels();

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
		$relationModel = Magic::LoadClassByName(Magic::ExpandClassName($substitutionClassName));

		if (null === $attributeConfig = ArrayHelper::getValue($relationModel->historyRules(), "attributes.{$attributeName}")) return $attributeValue;
		if (is_callable($attributeConfig)) {
			return $attributeConfig($attributeName, $attributeValue);
		}
		if (is_array($attributeConfig)) {//[className => valueAttribute]
			$fromModelName = ArrayHelper::key($attributeConfig);
			/** @var ActiveRecordExtended $fromModel */
			$fromModel = Magic::LoadClassByName($fromModelName);
			$modelValueAttribute = $attributeConfig[$fromModelName];
			return ArrayHelper::getValue($fromModel::findModel($attributeValue), $modelValueAttribute, $attributeValue);

		} else return $attributeConfig;//Можем вернуть прямо заданное значение
		//todo: добавить параметр конфига для скрытия атрибутов из истории

	}

	/**
	 * @param string $attributeName
	 * @param mixed $attributeValue
	 * @param string $relationModelName
	 * @return mixed
	 * @throws InvalidConfigException
	 * @throws Throwable
	 * @throws ReflectionException
	 * @throws UnknownClassException
	 */
	private function SubstituteAttributeValueOld(string $attributeName, $attributeValue, string $relationModelName) {
		$relationModelName = Magic::ExpandClassName($relationModelName);
		if ($this->requestModel->hasMethod('historyRelations') && ([] !== $modelHistoryRules = $this->requestModel->historyRelations()) && (null !== $substitutionRules = ArrayHelper::getValue($modelHistoryRules, "{$relationModelName}.substitutions"))) {//у класса задано описание подстановки между таблицами
			/** @var array $substitutionRules */
			foreach ($substitutionRules as $substitutionModelName => $substitutionRule) {
				$substituteCondition = ArrayHelper::getValue($substitutionRule, 'substitute');//substitution rule like ['user_id' => 'name'] (replace user_id in relational model by name from substitution model)
				if (null !== $substitutionAttributeName = ArrayHelper::getValue($substituteCondition, $attributeName)) {//задано правило подстановки
					$substitutionModelClass = Magic::LoadClassByName($substitutionModelName);
					$link = ArrayHelper::getValue($substitutionRule, 'link', new InvalidConfigException("'Link property is required in rule configuration'"));//link between models attributes like ['id' => 'group_id']
					if (is_callable($link)) {//closure configured
						$loadedClass = Magic::LoadClassByName($relationModelName);
						$loadedModel = $loadedClass::find()->where([$attributeName => $attributeValue])->one();
						$returnModel = $link($loadedModel, $substitutionModelClass);
						if (null === $returnModel) return $attributeValue;//Подстановку не удалось сделать, скорее всего записи в таблице удалены, покажем хоть что-то
						/*Варианты решения: 1) искать по истории последнее событие с удалённым объектом и раскручивать цепочку 2) не удалять, добавив флаг для консистентности*/
					} else {
						$linkKey = ArrayHelper::key($link);
						$returnModel = $substitutionModelClass::find()->where([$linkKey => $attributeValue])->one();
					}
					return (null === $returnModel)?null:$returnModel->$substitutionAttributeName;
				}
			}
		}
		return $attributeValue;
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

		$logRecordedModel = Magic::LoadClassByName(Magic::ExpandClassName($logRecord->model));
		if (null !== $labelsConfig = ArrayHelper::getValue($logRecordedModel->historyRules(), "eventConfig.actionLabels")) {
			if (is_callable($labelsConfig)) {
				$result->eventCaption = $labelsConfig($result->eventType, $result->eventTypeName);
			} elseif (is_array($labelsConfig)) {
				$result->eventCaption = ArrayHelper::getValue($labelsConfig, $result->eventType, $result->eventTypeName);
			} else $result->eventCaption = $labelsConfig;
		}

		$result->eventTime = $logRecord->timestamp;
		$result->objectName = $logRecord->model;
		$result->subject = Users::findModel($logRecord->user);
		$result->eventIcon = Icons::event_icon($result->eventType);

		$result->actions = $this->getEventActions($logRecord);
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

}