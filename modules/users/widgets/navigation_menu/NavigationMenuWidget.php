<?php
declare(strict_types = 1);

namespace app\modules\users\widgets\navigation_menu;

use app\modules\users\models\Users;
use yii\base\Widget;

/**
 * Class NavigationMenuWidget
 * @package app\modules\users\widgets\navigation_menu
 * @property Users $model
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
		return $this->render('navigation_menu', [
			'model' => $this->model
		]);
	}
}
