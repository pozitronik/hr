<?php
declare(strict_types = 1);

namespace app\modules\privileges\widgets\navigation_menu;

use app\helpers\Icons;
use app\modules\privileges\models\DynamicUserRights;
use app\widgets\navigation_menu\BaseNavigationMenuWidget;

/**
 * Class UserRightNavigationMenuWidget
 * @package app\modules\privileges\widgets\navigation_menu
 */
class UserRightNavigationMenuWidget extends BaseNavigationMenuWidget {
	public $className;

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 */
	public function run():string {
		if (is_a($this->model, DynamicUserRights::class)) {
			$this->_navigationItems = [
				[
					'label' => Icons::update().'Изменение',
					'url' => ['update', 'id' => $this->model->id]
				],
				[
					'label' => Icons::rule().'Новое правило',
					'url' => ['dynamic-rights/create']
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
		return '';//статические правила не поддерживают меню
	}
}
