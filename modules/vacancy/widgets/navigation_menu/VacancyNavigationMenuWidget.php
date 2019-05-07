<?php
declare(strict_types = 1);

namespace app\modules\vacancy\widgets\navigation_menu;

use app\helpers\IconsHelper;
use app\modules\groups\GroupsModule;
use app\modules\history\HistoryModule;
use app\modules\vacancy\models\Vacancy;
use app\modules\vacancy\VacancyModule;
use app\widgets\navigation_menu\BaseNavigationMenuWidget;
use Throwable;
use yii\base\InvalidConfigException;

/**
 * Class VacancyMenuWidget
 * @package app\modules\vacancy\widgets\navigation_menu
 * @property Vacancy $model
 */
class VacancyNavigationMenuWidget extends BaseNavigationMenuWidget {

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 * @throws InvalidConfigException
	 * @throws Throwable
	 */
	public function run():string {
		$this->_navigationItems = [
			[
				'menu' => true,
				'label' => IconsHelper::vacancy_red().'Создать вакансию',
				'url' => VacancyModule::to(['vacancy/create'])
			],
			[
				'label' => IconsHelper::update().'Изменение',
				'url' => VacancyModule::to(['vacancy/update', 'id' => $this->model->id])
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
