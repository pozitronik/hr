<?php
declare(strict_types = 1);

namespace app\models\prototypes;

use yii\base\Model;

/**
 * Class HistoryEvent
 * @package app\models\prototypes
 *
 * @property int $eventType Что сделал
 * @property string $eventTypeName Что сделал
 * @property string|null $eventIcon Иконка?
 * @property string $eventTime Во сколько сделал
 * @property string $objectName Где сделал
 * @property string $subjectName Кто сделал
 * @property string $action Что произошло
 */
class HistoryEvent extends Model implements HistoryEventInterface {
	public $eventType;
	public $eventTypeName;
	public $eventIcon;
	public $eventTime;
	public $objectName;
	public $subjectName;
	public $action;

	/**
	 * @return string
	 */
	public function __toString() {
		return "{$this->subjectName} {$this->eventTypeName} {$this->objectName}: {$this->action}";
	}
}
