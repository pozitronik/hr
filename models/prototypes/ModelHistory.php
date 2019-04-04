<?php
declare(strict_types = 1);

namespace app\models\prototypes;

use app\helpers\ArrayHelper;
use app\models\core\ActiveRecordLogger;
use app\modules\users\models\Users;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\db\ActiveRecord;

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
		$this->loggerModel = null === $this->loggerModel?ActiveRecordLogger::class:$this->loggerModel;
		$formName = $this->requestModel->formName();
		$modelKey = $this->requestModel->primaryKey;
		return $this->loggerModel::find()->where(['model' => $formName, 'model_key' => $modelKey])->all();
	}

	/**
	 * Высчитывает и описывает изменения на шаге
	 */
	private function populateChanges(ActiveRecordLoggerInterface $record):string {
		$diff = [[]];
		foreach ($record->old_attributes as $attributeName => $attributeValue) {
			if (null === $newAttributeValue = ArrayHelper::getValue($record->new_attributes, $attributeName)) {
				$diff[HistoryEvent::EVENT_DELETED][$attributeName] = [$attributeValue, $newAttributeValue];
			} else {
				$diff[HistoryEvent::EVENT_CHANGED][$attributeName] = [$attributeValue, $newAttributeValue];
			}
		}
		foreach ($record->new_attributes as $attributeName => $attributeValue) {
			if (null === $oldAttributeValue = ArrayHelper::getValue($record->old_attributes, $attributeName)) {
				$diff[HistoryEvent::EVENT_CREATED][$attributeName] = [$oldAttributeValue, $attributeValue];
			}//игнорируем изменения - они учтены на предыдущем шаге
		}

		$result = [];
		foreach ($diff[HistoryEvent::EVENT_CREATED] as $createdAttributesName => $changes) {
			$result[] = "Задано значение поля ".ArrayHelper::getValue($this->requestModel->attributeLabels(), $createdAttributesName, $createdAttributesName).": {$changes[1]}";
		}
		foreach ($diff[HistoryEvent::EVENT_CHANGED] as $changedAttributesName => $changes) {
			$result[] = "Изменено значение поля ".ArrayHelper::getValue($this->requestModel->attributeLabels(), $changedAttributesName, $changedAttributesName).": $changes[0] => $changes[1]";
		}
		foreach ($diff[HistoryEvent::EVENT_DELETED] as $deletedAttributesName => $changes) {
			$result[] = "Удалено значение поля ".ArrayHelper::getValue($this->requestModel->attributeLabels(), $deletedAttributesName, $deletedAttributesName).": $changes[0]";
		}

		return implode("\n", $result);
	}

	/**
	 * @param ActiveRecordLoggerInterface $record
	 * @return HistoryEventInterface
	 */
	public function populateRecord(ActiveRecordLoggerInterface $record):HistoryEventInterface {
		$result = new HistoryEvent();
		if (null === $record->model_key) {
			$result->eventType = empty($record->old_attributes)?HistoryEvent::EVENT_CREATED:HistoryEvent::EVENT_DELETED;
		} else $result->eventType = HistoryEvent::EVENT_CHANGED;

		$result->eventTime = $record->timestamp;
		$result->objectName = $record->model;
		$result->subject = Users::findModel($record->user);

		$result->action = $this->populateChanges($record);
		return $result;
	}

	/**
	 * @param ActiveRecordLoggerInterface[] $timeline
	 * @return HistoryEventInterface[]
	 */
	public function populateTimeline(array $timeline):array {
		$result = [];
		foreach ($timeline as $record) {
			$result[] = $this->populateRecord($record);
		}
		return $result;
	}

}