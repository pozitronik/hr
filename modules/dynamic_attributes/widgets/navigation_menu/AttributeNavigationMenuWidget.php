<?php
declare(strict_types = 1);

namespace app\modules\dynamic_attributes\widgets\navigation_menu;

use app\models\core\IconsHelper;
use app\modules\dynamic_attributes\models\DynamicAttributes;
use app\components\pozitronik\widgets\BaseNavigationMenuWidget;

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
				'label' => IconsHelper::update().'Изменение',
				'url' => ['update', 'id' => $this->model->id]
			],
			[
				'menu' => true,
				'label' => IconsHelper::delete().'Удаление',
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
