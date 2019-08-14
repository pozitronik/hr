<?php
declare(strict_types = 1);

namespace app\widgets\group_card;

use pozitronik\widgets\CachedWidget;
use app\modules\groups\models\Groups;
use Throwable;

/**
 * Class GroupSelectWidget
 * @package app\components\group_card
 *
 * @property Groups $group
 * @property bool $short
 */
class GroupCardWidget extends CachedWidget {
	public $group;
	public $short = false;

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init() {
		parent::init();
		GroupCardWidgetAssets::register($this->getView());
		$this->cacheNamePrefix = $this->group->id;
	}

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 * @throws Throwable
	 */
	public function run():string {
		return $this->render($this->short?'group_info':'group_card', [
			'group' => $this->group
		]);
	}
}
