<?php
declare(strict_types = 1);

namespace app\modules\users\widgets\navigation_menu;

use app\helpers\Icons;
use app\modules\users\models\Users;
use yii\base\Widget;

/**
 * Class NavigationMenuWidget
 * @package app\modules\users\widgets\navigation_menu
 * @property Users $model
 * @property int $mode
 */
class NavigationMenuWidget extends Widget {
	public const MODE_MENU = 0;
	public const MODE_TABS = 1;

	public $model;
	public $mode = self::MODE_TABS;

	private $_navigationItems = [];

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init() {
		parent::init();
		NavigationMenuWidgetAssets::register($this->getView());

		$this->_navigationItems = [
			[
				'label' => Icons::user().'Профиль',
				'url' => ['/users/users/profile', 'id' => $this->model->id]
			],
			[
				'label' => Icons::group().'Группы',
				'url' => ['/users/users/groups', 'id' => $this->model->id]
			],
			[
				'label' => Icons::attributes().'Атрибуты',
				'url' => ['/attributes/user', 'user_id' => $this->model->id]
			],
			[
				'label' => Icons::user_add().'Новый пользователь',
				'url' => '/users/users/create'
			]
		];

	}

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 */
	public function run():string {
		if ($this->model->isNewRecord) return '';
		switch ($this->mode) {
			case self::MODE_MENU:
				return $this->render('navigation_menu', [
					'items' => $this->_navigationItems
				]);
			break;
			default:
			case self::MODE_TABS:
				return $this->render('navigation_tabs', [
					'items' => $this->_navigationItems
				]);
			break;
		}

	}
}
