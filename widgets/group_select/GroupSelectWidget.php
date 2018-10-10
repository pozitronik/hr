<?php
declare(strict_types = 1);

namespace app\widgets\group_select;

use yii\base\Widget;

/**
 * Class GroupSelectWidget
 * @package app\components\group_select
 */
class GroupSelectWidget extends Widget {

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init() {
		parent::init();
		GroupSelectWidgetAssets::register($this->getView());
	}

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 */
	public function run():string {
		return $this->render('group_select');
	}
}
