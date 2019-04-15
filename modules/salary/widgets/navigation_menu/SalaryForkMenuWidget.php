<?php
declare(strict_types = 1);

namespace app\modules\salary\widgets\navigation_menu;

use app\helpers\Icons;
use app\modules\salary\models\SalaryFork;
use app\widgets\navigation_menu\BaseNavigationMenuWidget;

/**
 * @property SalaryFork $model
 */
class SalaryForkMenuWidget extends BaseNavigationMenuWidget {

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 */
	public function run():string {
		if ($this->model->isNewRecord) return '';

		$this->_navigationItems = [
			[
				'menu' => true,
				'label' => Icons::update().'Изменение',
				'url' => ['update', 'id' => $this->model->id]
			],
			[
				'menu' => true,
				'label' => Icons::history().'История изменений',
				'url' => ['/history/history/show', 'for' => $this->model->formName(), 'id' => $this->model->id]
			],
			[
				'menu' => true,
				'label' => Icons::delete().'Удаление',
				'url' => ['delete', 'id' => $this->model->id],
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
