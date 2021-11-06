<?php
declare(strict_types = 1);

namespace app\components\pozitronik\arlogger\models;

/**
 * Interface ActiveRecordLoggerInterface
 *
 * @property-read string $timestamp
 * @property int $user
 * @property string $model
 * @property int $model_key
 * @property array $old_attributes
 * @property array $new_attributes
 *
 * @property int $eventType
 * @property HistoryEvent $event
 *
 * @property string $indexView Index view
 * @property string $timelineView Timeline view
 */
interface ActiveRecordLoggerInterface {

	/**
	 * Вернуть историю запрошенного объекта
	 * @param int $modelKey
	 * @return self[]
	 */
	public function getHistory(int $modelKey):array;
}