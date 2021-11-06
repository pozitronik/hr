<?php
declare(strict_types = 1);

namespace app\modules\history\models;

use app\components\pozitronik\helpers\ArrayHelper;
use app\modules\users\models\Users;
use Exception;
use app\components\pozitronik\helpers\ReflectionHelper;
use Throwable;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;

/**
 * Class HistoryEvent
 *
 * @property int $eventType Что произошло. Добавление/изменение/удаление/свой тип
 * @property null|string $eventTypeName Строковое название события, null - по умолчанию
 * @property string|null $eventIcon Иконка?
 * @property string $eventTime Во сколько сделал
 * @property string $objectName Где сделал (имя класса)
 * @property null|Users $subject Кто сделал
 * @property HistoryEventAction[] $actions Набор изменений внутри одного события.
 * @property null|string $eventCaption Переопределить типовой заголовок события
 *
 * @property null|string|callable|array|false $actionsFormatter
 * @property TimelineEntry $timelineEntry
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
	public $actionsFormatter;

	/**
	 * Converts log event to timeline entry
	 * @return TimelineEntry
	 * @throws Throwable
	 */
	public function getTimelineEntry():TimelineEntry {
		if (null === $this->actionsFormatter) {
			$content = self::ActionsFormatterDefault($this->actions);//default formatter
		} elseif (is_string($this->actionsFormatter)) {
			$content = $this->actionsFormatter;
		} elseif (ReflectionHelper::is_closure($this->actionsFormatter)) {
			$content = call_user_func($this->actionsFormatter, $this->actions);
		} elseif (is_array($this->actionsFormatter)) {//['view', parameters]
			$view = ArrayHelper::getValue($this->actionsFormatter, 0, new InvalidConfigException('actionsFormatter array config must contain view path as first item'));
			$parameters = ArrayHelper::getValue($this->actionsFormatter, 1, []);
			$parameters['actions'] = $this->actions;
			$content = Yii::$app->view->render($view, $parameters);

		} else $content = null;

		return new TimelineEntry([
			'icon' => $this->eventIcon,
			'time' => $this->eventTime,
			'caption' => $this->eventCaption??$this->eventTypeName,
			'user' => $this->subject,
			'content' => $content
		]);
	}

	/**
	 * Форматирование массива событий по умолчанию
	 * @param HistoryEventAction[] $actions
	 * @return string
	 * @throws Exception
	 */
	public static function ActionsFormatterDefault(array $actions):string {
		return Yii::$app->view->render('actions', ['actions' => $actions]);
	}

	/**
	 * @return null|string
	 * @throws Throwable
	 */
	public function getEventTypeName():?string {
		return ArrayHelper::getValue(self::EVENT_TYPE_NAMES, $this->eventType);
	}
}
