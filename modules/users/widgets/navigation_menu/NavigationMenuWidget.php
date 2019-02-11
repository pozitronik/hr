<?php
declare(strict_types = 1);

namespace app\modules\users\widgets\navigation_menu;

use yii\base\Widget;

/**
 * Class GroupSelectWidget
 * Пустой шаблон виджета. Для быстрого использования копипастим под нужным именем, заменяем все *NavigationMenu* на нужное нам имя, и работаем
 * @package app\components\navigation_menu
 */
class NavigationMenuWidget extends Widget {

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init() {
		parent::init();
		NavigationMenuWidgetAssets::register($this->getView());
	}

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 */
	public function run():string {
		return $this->render('navigation_menu');
	}
}
