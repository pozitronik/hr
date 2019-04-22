<?php
declare(strict_types = 1);

namespace app\modules\vacancy\widgets\navigation_menu;

use app\helpers\Icons;
use app\widgets\navigation_menu\BaseNavigationMenuWidget;
use yii\base\InvalidConfigException;

/**
 * Class VacancyMenuWidget
 * @package app\modules\vacancy\widgets\navigation_menu
 */
class VacancyMenuWidget extends BaseNavigationMenuWidget {

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 * @throws InvalidConfigException
	 */
	public function run():string {
		$this->_navigationItems = [
			[
				'menu' => true,
				'label' => Icons::history().'История изменений',
				'url' => ['/history/history/show', 'for' => $this->model->formName(), 'id' => $this->model->id]
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
