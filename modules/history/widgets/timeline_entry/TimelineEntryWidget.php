<?php
declare(strict_types = 1);

namespace app\modules\history\widgets\timeline_entry;

use pozitronik\widgets\CachedWidget;
use app\modules\history\models\TimelineEntry;

/**
 * Виджет элемента таймлайна
 * Class TimelineWidget
 * @package app\widgets\timeline
 *
 * @property TimelineEntry $entry
 */
class TimelineEntryWidget extends CachedWidget {
	public $entry;

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init() {
		parent::init();
		TimelineEntryWidgetAssets::register($this->getView());
	}

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 */
	public function run():string {
		return $this->render('timeline_entry', [
			'entry' => $this->entry
		]);
	}
}
