<?php
declare(strict_types = 1);

namespace app\widgets\admin_control;

use yii\base\Widget;

/**
 * Class Admin_controlWidget
 * Пустой шаблон виджета. Для быстрого использования копипастим под нужным именем, заменяем все *Admin_control* на нужное нам имя, и работаем
 * @package app\components\admin_control
 */
class Admin_controlWidget extends Widget {
	public $model;

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init() {
		parent::init();
		Admin_controlWidgetAssets::register($this->getView());
	}

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 */
	public function run():string {
		return $this->render('admin_control', [
			'model' => $this->model
		]);
	}
}
