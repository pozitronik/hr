<?php
declare(strict_types = 1);

namespace app\modules\targets\widgets\interval;

use kartik\base\InputWidget;

/**
 * Class IntervalWidget
 * @package app\modules\targets\widgets\interval
 */
class IntervalWidget extends InputWidget {
	public $form;

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
		return $this->render('interval',[
			'model' => $this->model->{$this->attribute},
			'form' => $this->form
		]);
	}
}
