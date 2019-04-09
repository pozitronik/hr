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

	/**
	 * @return ActiveRecordLoggerInterface[]
	 * @throws InvalidConfigException
	 * @throws Throwable
	 * @throws ReflectionException
	 * @throws UnknownClassException
	 */
	public function getHistory():array {
		$this->loggerModel = $this->loggerModel??ActiveRecordLogger::class;
		$formName = $this->requestModel->formName();
		$modelKey = $this->requestModel->primaryKey;

		/** @var LCQuery $findCondition */
		$findCondition = $this->loggerModel::find()->where(['model' => $formName, 'model_key' => $modelKey]);//поиск по изменениям в основной таблице модели

		if ($this->requestModel->hasMethod('historyRelations') && [] !== $modelHistoryRules = $this->requestModel->historyRelations()) {/*Разбираем правила релейшенов в истории, собираем правила поиска по изменениям в таблицах релейшенов*/
			foreach ($modelHistoryRules as $ruleName => $ruleCondition) {
				$model = ArrayHelper::getValue($ruleCondition, 'model', new InvalidConfigException("'Model property is required in rule configuration'"));//full linked model name with namespace
				$link = ArrayHelper::getValue($ruleCondition, 'link', new InvalidConfigException("'Link property is required in rule configuration'"));//link between models attributes like ['id' => 'user_id']
				$linkKey = ArrayHelper::key($link);
				$linkValue = $link[$linkKey];
				$modelKey = $this->requestModel->$linkKey;
				if (null === $modelClass = Magic::LoadClassByName($model)) throw new InvalidConfigException("Class $model not found in application scope!");
				$findCondition->orWhere("model = '{$modelClass->formName()}' and (new_attributes->'$.{$linkValue}' = {$modelKey} or old_attributes->'$.{$linkValue}' = {$modelKey})");

			}
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
			if (isset($record->new_attributes, $attributeName)) {
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
			if (!isset($record->old_attributes, $attributeName) || null === ArrayHelper::getValue($record->old_attributes, $attributeName)) {
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
	 * @param string $attributeName
	 * @param mixed $attributeValue
	 * @param string $relationModelName
	 * @return mixed
	 * @throws InvalidConfigException
	 * @throws Throwable
	 * @throws ReflectionException
	 * @throws UnknownClassException
	 */
	private function SubstituteAttributeValue(string $attributeName, $attributeValue, string $relationModelName) {
		//todo: проверка на historyRelations может быть опущена, т.к. идёт наследование от Extended-модели
		if ($this->requestModel->hasMethod('historyRelations') && ([] !== $modelHistoryRules = $this->requestModel->historyRelations()) && (null !== $substitutionRules = ArrayHelper::getValue($modelHistoryRules, "{$relationModelName}.substitutions"))) {//у класса задано описание подстановки между таблицами
			/** @var array $substitutionRules */
			foreach ($substitutionRules as $substitutionRule) {
				$substituteCondition = ArrayHelper::getValue($substitutionRule, 'substitute');//substitution rule like ['user_id' => 'name'] (replace user_id in relational model by name from substitution model)
				if (null !== $substitutionAttributeName = ArrayHelper::getValue($substituteCondition, $attributeName)) {//задано правило подстановки
					$model = ArrayHelper::getValue($substitutionRule, 'model', new InvalidConfigException("'Model property is required in rule configuration'"));//full linked model name with namespace
					$link = ArrayHelper::getValue($substitutionRule, 'link', new InvalidConfigException("'Link property is required in rule configuration'"));//link between models attributes like ['id' => 'group_id']
					if (null === $modelClass = Magic::LoadClassByName($model)) throw new InvalidConfigException("Class $model not found in application scope!");
					$linkKey = ArrayHelper::key($link);
					return (null === $returnModel = $modelClass::find()->where([$linkKey => $attributeValue])->one())?null:$returnModel->$substitutionAttributeName;
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

		$result->eventCaption =  ArrayHelper::getValue($this->requestModel->historyRelations(),"{$logRecord->model}.label");
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