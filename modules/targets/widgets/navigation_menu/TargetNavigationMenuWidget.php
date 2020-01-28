<?php
declare(strict_types = 1);

namespace app\modules\targets\widgets\navigation_menu;

use app\helpers\IconsHelper;
use app\modules\graph\GraphModule;
use app\modules\history\HistoryModule;
use app\modules\targets\models\Targets;
use app\modules\targets\TargetsModule;
use app\modules\vacancy\VacancyModule;
use app\widgets\navigation_menu\BaseNavigationMenuWidget;
use Throwable;
use yii\base\InvalidConfigException;

/**
 * Class TargetsNavigationMenuWidget
 * @property Targets $model
 */
class TargetNavigationMenuWidget extends BaseNavigationMenuWidget {

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
				'url' => TargetsModule::to(['targets/profile', 'id' => $this->model->id])
			],
			[
				'label' => IconsHelper::subgroups().'Иерархия',
				'url' => TargetsModule::to(['targets/targets', 'id' => $this->model->id])
			],
			[
				'label' => IconsHelper::users().'Пользователи',
				'url' => TargetsModule::to(['targets/users', 'id' => $this->model->id])
			],
			[
				'label' => IconsHelper::vacancy().'Бюджеты',
				'url' => VacancyModule::to(['targets/budgets', 'id' => $this->model->id])
			],
			[
				'label' => IconsHelper::network().'Граф',
				'url' => GraphModule::to(['graph/target', 'id' => $this->model->id])
			],
			[
				'menu' => true,
				'label' => IconsHelper::add().'Новое задание',
				'url' => TargetsModule::to(['targets/create'])
			],
			[
				'menu' => true,
				'label' => IconsHelper::update().'Редактировать',
				'url' => TargetsModule::to(['targets/update', 'id' => $this->model->id])
			],
			[
				'menu' => true,
				'label' => IconsHelper::history().'История изменений',
				'url' => HistoryModule::to(['history/show', 'for' => $this->model->formName(), 'id' => $this->model->id])
			],
			[
				'menu' => true,
				'label' => IconsHelper::delete().'Удаление',
				'url' => TargetsModule::to(['targets/delete', 'id' => $this->model->id]),
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