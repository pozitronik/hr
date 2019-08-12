<?php
declare(strict_types = 1);

namespace app\widgets\search;

use pozitronik\widgets\CachedWidget;

/**
 * Class SearchWidget
 * @package app\widgets\search
 */
class SearchWidget extends CachedWidget {

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init() {
		parent::init();
		SearchWidgetAssets::register($this->getView());
	}

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 */
	public function run():string {
		return $this->render('search');
	}
}
