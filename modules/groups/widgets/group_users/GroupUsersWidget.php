<?php
declare(strict_types = 1);

namespace app\modules\groups\widgets\group_users;

use pozitronik\widgets\CachedWidget;
use app\modules\groups\models\Groups;
use Throwable;

/**
 * Class GroupUsersWidget
 * @package app\components\group_card
 *
 * @property Groups $group
 * @property array $options
 */
class GroupUsersWidget extends CachedWidget {
	public $group;
	public $options = [];

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init() {
		parent::init();
		GroupUsersWidgetAssets::register($this->getView());
		$this->cacheNamePrefix = $this->group->id;
	}

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 * @throws Throwable
	 */
	public function run():string {
		return $this->render('group_users', [
			'group' => $this->group,
			'options' => $this->options
		]);
	}
}
