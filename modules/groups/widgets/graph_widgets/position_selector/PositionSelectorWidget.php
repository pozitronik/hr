<?php
declare(strict_types = 1);

namespace app\modules\groups\widgets\graph_widgets\position_selector;

use yii\base\Widget;

/**
 * Class PositionSelectorWidget
 * @package app\widgets\position_selector
 */
class PositionSelectorWidget extends Widget {
	public $positionConfigurations = [];
	public $currentConfiguration = 0;

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init() {
		parent::init();
		PositionSelectorWidgetAssets::register($this->getView());
	}

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 */
	public function run():string {
		return $this->render('position_selector', [
			'positionConfigurations' => $this->positionConfigurations,
			'currentConfiguration' => $this->currentConfiguration
		]);
	}
}
