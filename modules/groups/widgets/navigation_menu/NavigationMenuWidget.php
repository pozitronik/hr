<?php
declare(strict_types = 1);

namespace app\modules\groups\widgets\navigation_menu;

use app\modules\groups\models\Groups;
use yii\base\Widget;

/**
 * Class NavigationMenuWidget
 * @package app\modules\users\widgets\navigation_menu
 * @property Groups $model
 */
class NavigationMenuWidget extends Widget {
	public $model;

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init() {
		parent::init();
		NavigationMenuWidgetAssets::register($this->getView());
	}

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 */
	public function run():string {
		return !$this->model->isNewRecord?$this->render('navigation_menu', [
			'model' => $this->model
		]):'';
	}
}
