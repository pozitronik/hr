<?php
declare(strict_types = 1);

namespace app\modules\users\widgets\navigation_menu;

use app\helpers\Icons;
use app\modules\users\models\Users;
use app\widgets\navigation_menu\BaseNavigationMenuWidget;

/**
 * Class NavigationMenuWidget
 * @package app\modules\users\widgets\navigation_menu
 * @property Users $model
 */
class NavigationMenuWidget extends BaseNavigationMenuWidget {

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 */
	public function run():string {
		if ($this->model->isNewRecord) return '';

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

		return parent::run();
	}
}
