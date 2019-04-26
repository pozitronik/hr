<?php
declare(strict_types = 1);

namespace app\modules\users\widgets\navigation_menu;

use app\helpers\Icons;
use app\modules\dynamic_attributes\models\DynamicAttributes;
use app\modules\export\ExportModule;
use app\modules\history\HistoryModule;
use app\modules\users\models\Users;
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
				'label' => Icons::user().'Профиль',
				'url' => Users::to(['users/profile', 'id' => $this->model->id])
			],
			[
				'label' => Icons::group().'Группы',
				'url' => Users::to(['users/groups', 'id' => $this->model->id])
			],
			[
				'label' => Icons::money().'Зарплатные данные',
				'url' => Users::to(['users/salary', 'id' => $this->model->id])
			],
			[
				'label' => Icons::attributes().'Атрибуты',
				'url' => DynamicAttributes::to(['user', 'user_id' => $this->model->id])
			],
			[
				'menu' => true,
				'label' => Icons::export().'Экспорт атрибутов',
				'url' => ExportModule::to(['attributes/user', 'id' => $this->model->id])
			],
			[
				'menu' => true,
				'label' => Icons::user_add().'Новый пользователь',
				'url' => Users::to(['users/create'])
			],
			[
				'menu' => true,
				'label' => Icons::history().'История изменений',
				'url' => HistoryModule::to(['history/show', 'for' => $this->model->formName(), 'id' => $this->model->id])
			],
			[
				'menu' => true,
				'label' => Icons::delete().'Удаление',
				'url' => Users::to(['users/delete', 'id' => $this->model->id]),
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
