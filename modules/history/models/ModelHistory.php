<?php
declare(strict_types = 1);

namespace app\modules\history\models;

use app\helpers\ArrayHelper;
use app\helpers\Icons;
use app\models\core\ActiveRecordLogger;
use app\models\core\ActiveRecordLoggerInterface;
use app\modules\users\models\Users;
use Throwable;
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
		$this->loggerModel = $this->loggerModel??ActiveRecordLogger::class;
		$formName = $this->requestModel->formName();
		$modelKey = $this->requestModel->primaryKey;
		return $this->loggerModel::find()->where(['model' => $formName, 'model_key' => $modelKey])->all();
	}

	/**
	 * Вытаскивает из записи описание изменений атрибутов, конвертируя их в набор HistoryEventAction
	 * @param ActiveRecordLoggerInterface $record
	 * @return HistoryEventAction[]
	 * @throws Throwable
	 */
	private function getEventActions(ActiveRecordLoggerInterface $record):array {
		$diff = [];
		foreach ($record->old_attributes as $attributeName => $attributeValue) {
			$eventAction = new HistoryEventAction(['attributeName' => $attributeName, 'attributeOldValue' => $attributeValue]);
			if (null === $newAttributeValue = ArrayHelper::getValue($record->new_attributes, $attributeName)) {
				$eventAction->type = HistoryEventAction::ATTRIBUTE_DELETED;
			} else {
				$eventAction->type = HistoryEventAction::ATTRIBUTE_CHANGED;
				$eventAction->attributeNewValue = $newAttributeValue;
			}
			$diff[] = $eventAction;
		}
		foreach ($record->new_attributes as $attributeName => $attributeValue) {
			$eventAction = new HistoryEventAction(['attributeName' => $attributeName, 'attributeNewValue' => $attributeValue]);
			if (null === $oldAttributeValue = ArrayHelper::getValue($record->old_attributes, $attributeName)) {
				$eventAction->type = HistoryEventAction::ATTRIBUTE_CREATED;
				$eventAction->attributeOldValue = $oldAttributeValue;
				$diff[] = $eventAction;
			}//игнорируем изменения - они учтены на предыдущем шаге

		}

		return $diff;
	}

	/**
	 * Переводит запись из лога в событие истории
	 * @param ActiveRecordLoggerInterface $logRecord
	 * @return HistoryEventInterface
	 * @throws Throwable
	 */
	public function getHistoryEvent(ActiveRecordLoggerInterface $logRecord):HistoryEventInterface {
		$result = new HistoryEvent();

		if (null === $logRecord->model_key) {
			$result->eventType = empty($logRecord->old_attributes)?HistoryEvent::EVENT_CREATED:HistoryEvent::EVENT_DELETED;
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