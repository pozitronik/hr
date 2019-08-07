<?php
declare(strict_types = 1);

namespace app\modules\graph\widgets\position_selector;

use app\models\core\CachedWidget;

/**
 * Временный виджет, обеспечивающий обработку контрола сохранения/загрузки позиций нод
 * Class PositionSelectorWidget
 * @package app\modules\graph\widgets\position_selector
 */
class PositionSelectorWidget extends CachedWidget {
	public $positionConfigurations = [];
	public $currentConfiguration = 'default';

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
