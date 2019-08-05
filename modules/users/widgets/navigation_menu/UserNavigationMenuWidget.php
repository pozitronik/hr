<?php
declare(strict_types = 1);

namespace app\modules\users\widgets\navigation_menu;

use app\helpers\IconsHelper;
use app\modules\dynamic_attributes\DynamicAttributesModule;
use app\modules\export\ExportModule;
use app\modules\graph\GraphModule;
use app\modules\history\HistoryModule;
use app\modules\users\models\Users;
use app\modules\users\UsersModule;
use app\widgets\navigation_menu\BaseNavigationMenuWidget;
use Throwable;
use yii\base\InvalidConfigException;

/**
 * Class UserNavigationMenuWidget
 * @package app\modules\users\widgets\navigation_menu
 * @property Users $model
 */
class UserNavigationMenuWidget extends BaseNavigationMenuWidget {

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 * @throws InvalidConfigException
	 * @throws Throwable
	 */
	public function run():string {
		if ($this->model->isNewRecord) return '';

		$this->_navigationItems = [
			[
				'label' => IconsHelper::user().'Профиль',
				'url' => UsersModule::to(['users/profile', 'id' => $this->model->id])
			],
			[
				'label' => IconsHelper::group().'Группы',
				'url' => UsersModule::to(['users/groups', 'id' => $this->model->id])
			],
			[
				'label' => IconsHelper::money().'Зарплатные данные',
				'url' => UsersModule::to(['users/salary', 'id' => $this->model->id])
			],
			[
				'label' => IconsHelper::network().'Граф',
				'url' => GraphModule::to(['graph/user', 'id' => $this->model->id])
			],
			[
				'label' => IconsHelper::attributes().'Атрибуты',
				'url' => DynamicAttributesModule::to(['user', 'user_id' => $this->model->id])
			],
			[
				'menu' => true,
				'label' => IconsHelper::export().'Экспорт атрибутов',
				'url' => ExportModule::to(['attributes/user', 'id' => $this->model->id])
			],
			[
				'menu' => true,
				'label' => IconsHelper::user_add().'Новый пользователь',
				'url' => UsersModule::to(['users/create'])
			],
			[
				'menu' => true,
				'label' => IconsHelper::users_edit().'Редактировать',
				'url' => UsersModule::to(['users/update', 'id' => $this->model->id])
			],
			[
				'menu' => true,
				'label' => IconsHelper::history().'История изменений',
				'url' => HistoryModule::to(['history/show', 'for' => $this->model->formName(), 'id' => $this->model->id])
			],
			[
				'menu' => true,
				'label' => IconsHelper::delete().'Удаление',
				'url' => UsersModule::to(['users/delete', 'id' => $this->model->id]),
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
