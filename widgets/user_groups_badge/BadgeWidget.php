<?php
declare(strict_types = 1);

namespace app\widgets\badge;

use yii\base\Widget;

/**
 * Class GroupSelectWidget
 * @package app\components\badge
 */
class BadgeWidget extends Widget {
	public $fullNamesCount = 2;//Количество имён групп, которые

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init() {
		parent::init();
		BadgeWidgetAssets::register($this->getView());
	}

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 */
	public function run():string {
		return $this->render('badge');
	}
}
