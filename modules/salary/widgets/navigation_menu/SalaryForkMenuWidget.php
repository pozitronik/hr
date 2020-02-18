<?php
declare(strict_types = 1);

namespace app\modules\salary\widgets\navigation_menu;

use app\models\core\IconsHelper;
use app\modules\history\HistoryModule;
use app\modules\salary\models\SalaryFork;
use pozitronik\widgets\BaseNavigationMenuWidget;
use Throwable;
use yii\base\InvalidConfigException;

/**
 * @property SalaryFork $model
 */
class SalaryForkMenuWidget extends BaseNavigationMenuWidget {

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 * @throws InvalidConfigException
	 * @throws Throwable
	 */
	public function run():string {
		if ($this->model->isNewRecord) return '';

		$this->_navigationItems = [
			[
				'menu' => true,
				'label' => IconsHelper::update().'Изменение',
				'url' => ['update', 'id' => $this->model->id]
			],
			[
				'menu' => true,
				'label' => IconsHelper::history().'История изменений',
				'url' => HistoryModule::to(['history/show', 'for' => $this->model->formName(), 'id' => $this->model->id])
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
