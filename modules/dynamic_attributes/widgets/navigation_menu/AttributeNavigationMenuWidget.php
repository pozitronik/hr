<?php
declare(strict_types = 1);

namespace app\modules\dynamic_attributes\widgets\navigation_menu;

use app\helpers\Icons;
use app\modules\dynamic_attributes\models\DynamicAttributes;
use app\widgets\navigation_menu\BaseNavigationMenuWidget;

/**
 * @property DynamicAttributes $model
 */
class AttributeNavigationMenuWidget extends BaseNavigationMenuWidget {
	public $className;

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 */
	public function run():string {
		$this->_navigationItems = [
			[
				'label' => Icons::update().'Изменение',
				'url' => ['update', 'id' => $this->model->id]
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
