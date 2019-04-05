<?php
declare(strict_types = 1);

namespace app\modules\history\models;

use app\modules\users\models\Users;

/**
 * Interface HistoryEventInterface
 *
 * @property int $eventType Что сделал
 * @property string $eventTypeName Что сделал
 * @property string|null $eventIcon Иконка?
 * @property string $eventTime Во сколько сделал
 * @property string $objectName Где сделал
 * @property null|Users $subject Кто сделал
 * @property HistoryEventAction[] $actions Что произошло
 */
interface HistoryEventInterface {
	const EVENT_CREATED = 0;
	const EVENT_CHANGED = 1;
	const EVENT_DELETED = 2;

	/**
	 * Converts log event to timeline entry
	 * @return TimelineEntry
	 */
	public function asTimelineEntry():TimelineEntry;
}