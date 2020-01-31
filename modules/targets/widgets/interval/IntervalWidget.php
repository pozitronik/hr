<?php
declare(strict_types = 1);

namespace app\modules\targets\widgets\interval;

use app\modules\targets\models\TargetsPeriods;
use kartik\base\InputWidget;

/**
 * Class IntervalWidget
 * @package app\modules\targets\widgets\interval
 *
 * prototype
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
		return (null === $modelValue = $this->model->{$this->attribute})?
			$this->render('interval', [
				'model' => new TargetsPeriods(['target_id' => $this->model->id]),
				'form' => $this->form
			]):$this->render('interval', [
				'model' => $modelValue,
				'form' => $this->form
			]);
	}
}
