<?php
declare(strict_types = 1);

namespace app\modules\groups\widgets\navigation_menu;

use app\helpers\Icons;
use app\modules\export\ExportModule;
use app\modules\groups\models\Groups;
use app\widgets\navigation_menu\BaseNavigationMenuWidget;
use Throwable;
use yii\base\InvalidConfigException;

/**
 * Class GroupNavigationMenuWidget
 * @property Groups $model
 */
class GroupNavigationMenuWidget extends BaseNavigationMenuWidget {

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 * @throws InvalidConfigException
	 * @throws Throwable
	 */
	public function run():string {
		$this->_navigationItems = [
			[
				'label' => Icons::group().'Профиль',
				'url' => Groups::to(['groups/profile', 'id' => $this->model->id])
			],
			[
				'label' => Icons::subgroups().'Иерархия',
				'url' => Groups::to(['groups/groups', 'id' => $this->model->id])
			],
			[
				'label' => Icons::users().'Пользователи',
				'url' => Groups::to(['groups/users', 'id' => $this->model->id])
			],
			[
				'label' => Icons::vacancy().'Вакансии',
				'url' => ['/vacancy/groups/index', 'id' => $this->model->id]
			],
			[
				'menu' => true,
				'label' => Icons::vacancy_red().'Создать вакансию',
				'url' => ['/vacancy/vacancy/create', 'group' => $this->model->id]
			],
			[
				'label' => Icons::network().'Граф',
				'url' => Groups::to(['groups/tree', 'id' => $this->model->id])
			],
			[
				'menu' => true,
				'label' => Icons::hierarchy().'Иерархия пользователей',
				'url' => Groups::to(['groups/users-hierarchy', 'id' => $this->model->id])
			],
			[
				'menu' => true,
				'label' => Icons::hierarchy_red().'Иерархия пользователей (с ролями)',
				'url' => Groups::to(['groups/users-hierarchy', 'showRolesSelector' => true, 'id' => $this->model->id])
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
				'url' => Groups::to(['groups/create'])
			],
			[
				'menu' => true,
				'label' => Icons::export().'Экспорт атрибутов пользователей',
				'url' => ExportModule::to(['attributes/group', 'id' => $this->model->id])
			],
			[
				'menu' => true,
				'label' => Icons::export_red().'Экспорт атрибутов пользователей (с учётом иерархии)',
				'url' => ExportModule::to(['attributes/group', 'id' => $this->model->id, 'hierarchy' => true])
			],
			[
				'menu' => true,
				'label' => Icons::history().'История изменений',
				'url' => ['/history/history/show', 'for' => $this->model->formName(), 'id' => $this->model->id]
			],
			[
				'menu' => true,
				'label' => Icons::delete().'Удаление',
				'url' => Groups::to(['groups/delete', 'id' => $this->model->id]),
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
