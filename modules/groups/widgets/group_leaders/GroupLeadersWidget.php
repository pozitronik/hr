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
 * @property bool $showImportant -- кроме лидеров показывать и важных юзеров
 */
class GroupLeadersWidget extends CachedWidget {
	public $group;
	public $showImportant = false;

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init() {
		parent::init();
		GroupLeadersWidgetAssets::register($this->getView());
		$this->cacheNamePrefix = $this->group->id.'-'.$this->showImportant;
	}

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 * @throws Throwable
	 */
	public function run():string {
		$leaders = $this->group->leaders;
		if ($this->showImportant) {
			$leaders = array_merge($leaders, $this->group->important);
		}
		return $this->render('group_leaders', [
			'leaders' => $leaders,
			'groupId' => $this->group->id
		]);
	}
}
