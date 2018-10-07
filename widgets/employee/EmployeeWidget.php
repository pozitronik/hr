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
	public $mode = 'employee';

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
		if ('employee' === $this->mode) {
			return $this->render('employee', [
				'model' => $this->user
			]);
		}
		return $this->render('boss', [
			'model' => $this->user
		]);

	}
}
