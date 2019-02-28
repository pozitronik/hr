<?php
declare(strict_types = 1);

namespace app\modules\dynamic_attributes\widgets\navigation_menu;

use app\helpers\Icons;
use app\modules\dynamic_attributes\models\DynamicAttributeProperty;
use app\modules\dynamic_attributes\models\DynamicAttributes;
use app\widgets\navigation_menu\BaseNavigationMenuWidget;

/**
 * @property DynamicAttributeProperty $model
 * @property DynamicAttributes $attribute
 */
class AttributePropertyNavigationMenuWidget extends BaseNavigationMenuWidget {
	public $className;
	public $attribute;

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 */
	public function run():string {
		$this->_navigationItems = [
			[
				'label' => Icons::update().'Изменение',
				'url' => ['update', 'attribute_id' => $this->attribute->id, 'property_id' => $this->model->id]
			],
			[
				'menu' => true,
				'label' => Icons::delete().'Удаление',
				'url' => ['property-delete', 'attribute_id' => $this->attribute->id, 'property_id' => $this->model->id],
				'linkOptions' => [
					'title' => 'Удалить запись',
					'data' => [
						'confirm' => 'Вы действительно хотите восстановить запись?',
						'method' => 'post'
					]
				]
			]
		];

		return parent::run();
	}
}