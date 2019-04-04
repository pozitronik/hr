<?php
declare(strict_types = 1);

namespace app\models\prototypes;

use yii\base\Model;

/**
 * Class HistoryEvent
 * @package app\models\prototypes
 */
class HistoryEvent extends Model implements HistoryEventInterface {

	/**
	 * @return string
	 */
	public function __toString() {
		return "{$this->subjectName} {$this->eventTypeName} {$this->objectName}: {$this->action}";
	}
}
