<?php
declare(strict_types = 1);

namespace app\modules\history\models;

use app\helpers\ArrayHelper;
use app\helpers\Icons;
use app\models\core\ActiveRecordLogger;
use app\models\core\ActiveRecordLoggerInterface;
use app\models\core\LCQuery;
use app\models\core\Magic;
use app\modules\users\models\Users;
use Throwable;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * Модель истории изменений объекта (предполагается, что это ActiveRecord, но по факту это любая модель с атрибутами)
 *
 * @property ActiveRecordLoggerInterface $loggerModel AR-интерфейс для работы с базой логов
 * @property ActiveRecord $requestModel Модель, для которой запрашиваем историю
 */
class ModelHistory extends Model {
	public $loggerModel;
	public $requestModel;

	/**
	 * @return ActiveRecordLoggerInterface[]
	 * @throws InvalidConfigException
	 */
	public function getHistory():array {
		$this->loggerModel = $this->loggerModel??ActiveRecordLogger::class;
		$formName = $this->requestModel->formName();
		$modelKey = $this->requestModel->primaryKey;

		/** @var LCQuery $findCondition */
		$findCondition = $this->loggerModel::find()->where(['model' => $formName, 'model_key' => $modelKey]);//поиск по изменениям в основной таблице модели

		/*Разбираем правила релейшенов в истории, собираем правила поиска по изменениям в таблицах релейшенов*/
		$modelHistoryRules = $this->requestModel->historyRelations();

		foreach ($modelHistoryRules as $ruleName => $ruleCondition) {
			$model = ArrayHelper::getValue($ruleCondition, 'model');
			$attribute = ArrayHelper::getValue($ruleCondition, 'attribute');

			$modelFormName = (null !== $modelClass = Magic::LoadClassByName($model))?$modelClass->formName():null;//temp code
			$sqlCondition = "model = '{$modelFormName}' and (new_attributes->'$.{$attribute}' = {$modelKey} or old_attributes->'$.{$attribute}' = {$modelKey})";

			$findCondition->orWhere($sqlCondition);
		}
		Yii::debug($findCondition->createCommand()->rawSql, 'sql');

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
					'attributeOldValue' => $this->SubstituteAttributeValue($attributeName, $attributeValue, $record->modelClass),
					'type' => HistoryEventAction::ATTRIBUTE_CHANGED,
					'attributeNewValue' => $this->SubstituteAttributeValue($attributeName, $record->new_attributes[$attributeName], $record->modelClass)
				]);
			} else {
				$diff[] = new HistoryEventAction([
					'attributeName' => ArrayHelper::getValue($labels, $attributeName, $attributeName),
					'attributeOldValue' => $this->SubstituteAttributeValue($attributeName, $attributeValue, $record->modelClass),
					'type' => HistoryEventAction::ATTRIBUTE_DELETED
				]);

			}
		}
		$e = array_diff_key($record->new_attributes, $record->old_attributes);

		foreach ($e as $attributeName => $attributeValue) {
			if (!isset($record->old_attributes, $attributeName) || null === ArrayHelper::getValue($record->old_attributes, $attributeName)) {
				$diff[] = new HistoryEventAction([
					'attributeName' => ArrayHelper::getValue($labels, $attributeName, $attributeName),
					'attributeNewValue' => $this->SubstituteAttributeValue($attributeName, $attributeValue, $record->modelClass),
					'type' => HistoryEventAction::ATTRIBUTE_CREATED
				]);
			}
		}

		return $diff;
	}

	/**
	 * @param string $attributeName
	 * @param mixed $attributeValue
	 * @param string $modelName
	 * @return mixed
	 */
	private function SubstituteAttributeValue(string $attributeName, $attributeValue, object $modelClass) {
		if ($modelClass->hasMethod('historyRelationsOut')) {
			$rules = $modelClass->historyRelationsOut();
			foreach ($rules as $ruleName => $ruleCondition) {
				$modelName = ArrayHelper::getValue($ruleCondition, 'model');
				$substituteModelClass = Magic::LoadClassByName($modelName);
				$link = ArrayHelper::getValue($ruleCondition, 'link');
				if (null === $substitutionAttribute = ArrayHelper::getValue(array_flip($link), $attributeName)) {
					continue;
				} else {
					$substitute = ArrayHelper::getValue($ruleCondition, 'substitute');//['group_id' => 'name]
					if (null !== $substitutionAttributeName = ArrayHelper::getValue($substitute,$attributeName)) {
						$substitutionValue = $modelClass::find()->where([$attributeName => $attributeValue])->one();

						$resultModel = ($substituteModelClass::find()->where([$substitutionAttribute => $substitutionValue])->one());

						return $resultModel->$substitutionAttributeName;
					}


				}

			}
		}

		return $attributeValue;//no substitutions found
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