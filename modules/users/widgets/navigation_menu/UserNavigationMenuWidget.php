<?php
declare(strict_types = 1);

namespace app\modules\users\widgets\navigation_menu;

use app\helpers\Icons;
use app\modules\users\models\Users;
use app\widgets\navigation_menu\BaseNavigationMenuWidget;

/**
 * Class UserNavigationMenuWidget
 * @package app\modules\users\widgets\navigation_menu
 * @property Users $model
 */
class UserNavigationMenuWidget extends BaseNavigationMenuWidget {

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
				'menu' => true,
				'label' => Icons::export().'Экспорт атрибутов',
				'url' => ['/export/attributes/user', 'id' => $this->model->id]
			],
			[
				'menu' => true,
				'label' => Icons::user_add().'Новый пользователь',
				'url' => '/users/users/create'
			],
			[
				'menu' => true,
				'label' => Icons::delete().'Удаление',
				'url' => ['/users/users/delete', 'id' => $this->model->id],
				'linkOptions' => [
					'title' => 'Удалить запись',
					'data' => [
						'confirm' => $this->model->deleted?'Вы действительно хотите восстановить запись?':'Вы действительно хотите удалить запись?',
						'method' => 'post'
					]
				]
			]
		];

		return parent::run();
	}
}
