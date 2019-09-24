<?php
declare(strict_types = 1);

namespace app\modules\groups\widgets\group_leaders;

use app\modules\groups\models\Groups;
use pozitronik\widgets\CachedWidget;
use Throwable;

/**
 * Class GroupLeadersWidget
 * @package app\modules\groups\widgets\group_users
 *
 * @property Groups $group
 * @property array $options
 */
class GroupLeadersWidget extends CachedWidget {
	public $group;
	public $options = [];

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init() {
		parent::init();
		GroupLeadersWidgetAssets::register($this->getView());
		$this->cacheNamePrefix = $this->group->id;
	}
//todo: баг для группы 102 некорректный рендер в плитке
	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 * @throws Throwable
	 */
	public function run():string {
		return $this->render('group_leaders', [
			'group' => $this->group
		]);
	}
}
