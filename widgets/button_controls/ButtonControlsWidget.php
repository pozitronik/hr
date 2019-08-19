<?php
declare(strict_types = 1);

namespace app\widgets\button_controls;

use pozitronik\widgets\CachedWidget;

/**
 * Class ButtonControlsWidget
 * @package app\widgets\button_controls
 */
class ButtonControlsWidget extends CachedWidget {

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init() {
		parent::init();
		ButtonControlsWidgetAssets::register($this->getView());
	}

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 */
	public function run():string {
		return $this->render('button_controls');
	}
}
