<?php
declare(strict_types = 1);

namespace app\modules\privileges\widgets\navigation_menu;

use app\models\core\IconsHelper;
use app\modules\privileges\models\Privileges;
use app\components\pozitronik\navigationwidget\BaseNavigationMenuWidget;

/**
 * Class ReferenceNavigationMenuWidget
 * @property Privileges $model
 */
class PrivilegesNavigationMenuWidget extends BaseNavigationMenuWidget {
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
				'label' => IconsHelper::rule().'Новое правило',
				'url' => ['dynamic-rights/create']
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
