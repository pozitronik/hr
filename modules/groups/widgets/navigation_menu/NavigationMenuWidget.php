<?php
declare(strict_types = 1);

namespace app\modules\groups\widgets\navigation_menu;

use app\helpers\Icons;
use app\modules\groups\models\Groups;
use app\widgets\navigation_menu\BaseNavigationMenuWidget;

/**
 * Class NavigationMenuWidget
 * @property Groups $model
 */
class NavigationMenuWidget extends BaseNavigationMenuWidget {

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 */
	public function run():string {
		$this->_navigationItems = [
			[
				'label' => Icons::group().'Профиль',
				'url' => ['/groups/groups/profile', 'id' => $this->model->id]
			],
			[
				'label' => Icons::subgroups().'Иерархия',
				'url' => ['/groups/groups/groups', 'id' => $this->model->id]
			],
			[
				'label' => Icons::users().'Пользователи',
				'url' => ['/groups/groups/users', 'id' => $this->model->id]
			],
			[
				'label' => Icons::network().'Граф',
				'url' => ['/groups/groups/tree', 'id' => $this->model->id]
			],
			[
				'menu' => true,
				'label' => Icons::hierarchy().'Иерархия пользователей',
				'url' => ['/groups/groups/users-hierarchy', 'id' => $this->model->id]
			],
			[
				'menu' => true,
				'label' => Icons::hierarchy_red().'Иерархия пользователей (с ролями)',
				'url' => ['/groups/groups/users-hierarchy', 'showRolesSelector' => true, 'id' => $this->model->id]
			],
			[
				'menu' => true,
				'label' => Icons::users_edit().'Редактировать пользователей',
				'url' => ['/users/bunch/index', 'group_id' => $this->model->id]
			],
			[
				'menu' => true,
				'label' => Icons::users_edit_red().'Редактировать пользователей (с учётом иерархии)',
				'url' => ['/users/bunch/index', 'group_id' => $this->model->id, 'hierarchy' => true]
			],
			[
				'menu' => true,
				'label' => Icons::add().'Новая группа',
				'url' => ['/groups/groups/create']
			],
			[
				'menu' => true,
				'label' => Icons::delete().'Удаление',
				'url' => ['/groups/groups/delete', 'id' => $this->model->id],
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
