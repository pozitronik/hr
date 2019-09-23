<?php
declare(strict_types = 1);

namespace app\modules\groups\widgets\group_card;

use pozitronik\widgets\CachedWidget;
use app\modules\groups\models\Groups;
use Throwable;

/**
 * Class GroupSelectWidget
 * @package app\components\group_card
 *
 * @property Groups $group
 * @property array $options Массив произвольных параметров, передаваемых внутрь вьюхи, и учитываемый только ей.
 */
class GroupCardWidget extends CachedWidget {
	public $group;
	public $options = [];

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
		return $this->render('group_card', [
			'group' => $this->group,
			'options' => $this->options
		]);
	}
}
