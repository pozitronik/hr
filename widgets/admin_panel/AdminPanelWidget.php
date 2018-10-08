<?php
declare(strict_types = 1);

namespace app\widgets\admin_panel;

use yii\base\Widget;

/**
 * Class AdminPanelWidget
 * Пустой шаблон виджета. Для быстрого использования копипастим под нужным именем, заменяем все *AdminPanel* на нужное нам имя, и работаем
 * @package app\components\admin_panel
 */
class AdminPanelWidget extends Widget {

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init() {
		parent::init();
		AdminPanelWidgetAssets::register($this->getView());
	}

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 */
	public function run():string {
		return $this->render('admin_panel');
	}
}
