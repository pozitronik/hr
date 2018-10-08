<?php
declare(strict_types = 1);

namespace app\widgets\template;

use yii\base\Widget;

/**
 * Class TemplateWidget
 * Пустой шаблон виджета. Для быстрого использования копипастим под нужным именем, заменяем все *Template* на нужное нам имя, и работаем
 * @package app\components\template
 */
class TemplateWidget extends Widget {

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
		return $this->render('template');
	}
}
