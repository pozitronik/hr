<?php
declare(strict_types = 1);

namespace app\widgets\group_select;

use yii\base\Widget;

/**
 * Class GroupSelectWidget
 * @package app\components\group_select
 *
 * @property array $data
 * @property boolean $multiple
 */
class GroupSelectWidget extends Widget {
	public $data;
	public $multiple = false;


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
