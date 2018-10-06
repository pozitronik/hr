<?php
declare(strict_types = 1);

namespace app\widgets\employee;

use yii\base\Widget;

/**
 * Class EmployeeWidget
 * @package app\components\employee
 */
class EmployeeWidget extends Widget {
	public $user;

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init() {
		parent::init();
		EmployeeWidgetAssets::register($this->getView());
	}

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 */
	public function run():string {
		return $this->render('employee', [
			'model' => $this->user
		]);
	}
}
