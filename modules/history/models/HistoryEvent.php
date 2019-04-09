<?php
declare(strict_types = 1);

namespace app\modules\history\models;

use app\helpers\ArrayHelper;
use app\modules\users\models\Users;
use Exception;
use kartik\grid\DataColumn;
use Throwable;
use yii\base\Model;
use yii\data\ArrayDataProvider;
use kartik\grid\GridView;
use yii\i18n\Formatter;

/**
 * Class HistoryEvent
 *
 * @property int $eventType Что сделал
 * @property null|string $eventTypeName Что сделал
 * @property string|null $eventIcon Иконка?
 * @property string $eventTime Во сколько сделал
 * @property string $objectName Где сделал
 * @property null|Users $subject Кто сделал
 * @property HistoryEventAction[] $actions Что произошло
 * @property null|string $eventCaption Переопределить типовой заголовок события
 */
class HistoryEvent extends Model implements HistoryEventInterface {
	public $eventType;
	public $eventCaption;
	public $eventIcon;
	public $eventTime;
	public $objectName;
	public $subject;
	public $subjectId;
	public $actions;

	/**
	 * Converts log event to timeline entry
	 * @return TimelineEntry
	 * @throws Exception
	 */
	public function asTimelineEntry():TimelineEntry {
		$userInfo = (null === $this->subject)?'':" пользователем {$this->subject->username}";
		return new TimelineEntry([
			'icon' => $this->eventIcon,
			'time' => $this->eventTime,
			'header' => (null === $this->eventCaption?$this->eventTypeName:$this->eventCaption).$userInfo,
			'content' => $this->getActionsTable()
		]);
	}

	/**
	 * @return string
	 * @throws Exception
	 */
	public function getActionsTable():string {
		$provider = new ArrayDataProvider([
			'allModels' => $this->actions,
			'sort' => [
				'attributes' => ['type', 'attributeName']
			]
		]);

		return GridView::widget([
			'dataProvider' => $provider,
			'summary' => false,
			'formatter' => [
				'class' => Formatter::class,
				'nullDisplay' => ''
			],
			'columns' => [
				[
					'class' => DataColumn::class,
					'attribute' => 'typeName',
					'group' => true
				],
				[
					'attribute' => 'attributeName'
				],
				[
					'attribute' => 'attributeOldValue'
				],
				[
					'attribute' => 'attributeNewValue'
				]
			]
		]);
	}

	/**
	 * @return null|string
	 * @throws Throwable
	 */
	public function getEventTypeName():?string {
		return ArrayHelper::getValue(self::EVENT_TYPE_NAMES, $this->eventType);
	}
}
