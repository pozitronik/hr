<?php
declare(strict_types = 1);

namespace app\modules\references\widgets\navigation_menu;

use app\helpers\Icons;
use app\modules\references\models\Reference;
use app\widgets\navigation_menu\BaseNavigationMenuWidget;
use yii\base\InvalidConfigException;

/**
 * Class ReferenceNavigationMenuWidget
 * @property Reference $model
 * @property string $className
 */
class ReferenceNavigationMenuWidget extends BaseNavigationMenuWidget {
	public $className;

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 * @throws InvalidConfigException
	 */
	public function run():string {
		$this->_navigationItems = [
			[
				'label' => Icons::view().'Просмотр',
				'url' => ['/references/references/view', 'id' => $this->model->id, 'class' => $this->className]
			],
			[
				'label' => Icons::update().'Изменение',
				'url' => ['/references/references/update', 'id' => $this->model->id, 'class' => $this->className]
			],
			[
				'menu' => true,
				'label' => Icons::history().'История изменений',
				'url' => ['/history/history/show', 'for' => $this->model->formName(), 'id' => $this->model->id]
			],
			[
				'menu' => true,
				'label' => Icons::delete().'Удаление',
				'url' => ['/references/references/delete', 'id' => $this->model->id, 'class' => $this->className],
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
