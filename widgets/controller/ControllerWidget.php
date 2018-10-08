<?php
declare(strict_types = 1);

namespace app\widgets\controller;

use app\models\core\WigetableController;
use yii\base\Widget;

/**
 * Class ControllerWidget
 * Отображение WigetableController в виде виджетов/меню
 * @package app\components\controller
 *
 * @property WigetableController $model
 */
class ControllerWidget extends Widget {
	public $model;

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init() {
		parent::init();
		ControllerWidgetAssets::register($this->getView());
	}

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 */
	public function run():string {
		return $this->render('controller', [
			'model' => $this->model
		]);
	}
}
