<?php
declare(strict_types = 1);

namespace app\modules\targets\widgets\interval;

use pozitronik\widgets\CachedWidget;

/**
 * Class GroupSelectWidget
 * Пустой шаблон виджета. Для быстрого использования копипастим под нужным именем, заменяем все *Interval* на нужное нам имя, и работаем
 * @package app\components\interval
 */
class IntervalWidget extends CachedWidget {

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init() {
		parent::init();
		IntervalWidgetAssets::register($this->getView());
	}

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 */
	public function run():string {
		return $this->render('interval');
	}
}
