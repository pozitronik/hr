<?php
declare(strict_types = 1);

namespace app\modules\privileges\widgets\navigation_menu;

use pozitronik\helpers\IconsHelper;
use app\modules\privileges\models\DynamicUserRights;
use app\modules\privileges\models\UserRight;
use pozitronik\widgets\BaseNavigationMenuWidget;

/**
 * Class UserRightNavigationMenuWidget
 * @package app\modules\privileges\widgets\navigation_menu
 * @property UserRight $model
 */
class UserRightNavigationMenuWidget extends BaseNavigationMenuWidget {
	public $className;

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 */
	public function run():string {
		if (is_a($this->model, DynamicUserRights::class)) {
			/** @noinspection PhpUndefinedFieldInspection */
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
		return '';//статические правила не поддерживают меню
	}
}
