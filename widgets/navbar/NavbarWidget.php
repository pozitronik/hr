<?php
declare(strict_types = 1);

namespace app\widgets\navbar;

use pozitronik\widgets\CachedWidget;
use app\modules\users\models\Users;

/**
 * Class NavbarWidget
 * Пустой шаблон виджета. Для быстрого использования копипастим под нужным именем, заменяем все *Navbar* на нужное нам имя, и работаем
 * @package app\components\navbar
 */
class NavbarWidget extends CachedWidget {
	public $user;

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init() {
		parent::init();
		NavbarWidgetAssets::register($this->getView());
	}

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 */
	public function run():string {

		return $this->render('navbar',[
			'user' => $this->user?:new Users()
		]);
	}
}
