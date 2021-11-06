<?php
declare(strict_types = 1);

namespace app\modules\groups\widgets\navigation_menu;

use app\models\core\IconsHelper;
use app\modules\export\ExportModule;
use app\modules\graph\GraphModule;
use app\modules\groups\GroupsModule;
use app\modules\groups\models\Groups;
use app\modules\history\HistoryModule;
use app\modules\targets\TargetsModule;
use app\modules\vacancy\VacancyModule;
use app\components\pozitronik\cachedwidget\BaseNavigationMenuWidget;
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
				'label' => IconsHelper::group().'Профиль',
				'url' => GroupsModule::to(['groups/profile', 'id' => $this->model->id])
			],
			[
				'label' => IconsHelper::subgroups().'Иерархия',
				'url' => GroupsModule::to(['groups/groups', 'id' => $this->model->id])
			],
			[
				'label' => IconsHelper::users().'Пользователи',
				'url' => GroupsModule::to(['groups/users', 'id' => $this->model->id])
			],
			[
				'label' => IconsHelper::targets().'Цели',
				'url' => TargetsModule::to(['targets/group', 'id' => $this->model->id])
			],
			[
				'label' => IconsHelper::vacancy().'Вакансии',
				'url' => VacancyModule::to(['groups/index', 'id' => $this->model->id])
			],
			[
				'menu' => true,
				'label' => IconsHelper::vacancy_red().'Создать вакансию',
				'url' => VacancyModule::to(['vacancy/create', 'group' => $this->model->id])
			],
			[
				'label' => IconsHelper::network().'Граф',
				'url' => GraphModule::to(['graph/group', 'id' => $this->model->id])
			],
			[
				'label' => IconsHelper::statistic().'Статистика',
				'url' => GroupsModule::to(['groups/attributes-statistics', 'id' => $this->model->id])
			],
			[
				'menu' => true,
				'label' => IconsHelper::hierarchy().'Иерархия пользователей',
				'url' => GroupsModule::to(['groups/users-hierarchy', 'id' => $this->model->id])
			],
			[
				'menu' => true,
				'label' => IconsHelper::hierarchy_red().'Иерархия пользователей (с ролями)',
				'url' => GroupsModule::to(['groups/users-hierarchy', 'showRolesSelector' => true, 'id' => $this->model->id])
			],
			[
				'menu' => true,
				'label' => IconsHelper::users_edit().'Редактировать пользователей',
				'url' => ['/users/bunch/index', 'group_id' => $this->model->id]
			],
			[
				'menu' => true,
				'label' => IconsHelper::users_edit_red().'Редактировать пользователей (с учётом иерархии)',
				'url' => ['/users/bunch/index', 'group_id' => $this->model->id, 'hierarchy' => true]
			],
			[
				'menu' => true,
				'label' => IconsHelper::add().'Новая группа',
				'url' => GroupsModule::to(['groups/create'])
			],
			[
				'menu' => true,
				'label' => IconsHelper::export().'Экспорт атрибутов пользователей',
				'url' => ExportModule::to(['attributes/group', 'id' => $this->model->id])
			],
			[
				'menu' => true,
				'label' => IconsHelper::export_red().'Экспорт атрибутов пользователей (с учётом иерархии)',
				'url' => ExportModule::to(['attributes/group', 'id' => $this->model->id, 'hierarchy' => true])
			],
			[
				'menu' => true,
				'label' => IconsHelper::update().'Редактировать',
				'url' => GroupsModule::to(['groups/update', 'id' => $this->model->id])
			],
			[
				'menu' => true,
				'label' => IconsHelper::history().'История изменений',
				'url' => HistoryModule::to(['history/show', 'for' => $this->model->formName(), 'id' => $this->model->id])
			],
			[
				'menu' => true,
				'label' => IconsHelper::delete().'Удаление',
				'url' => GroupsModule::to(['groups/delete', 'id' => $this->model->id]),
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
