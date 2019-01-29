<?php
declare(strict_types = 1);

namespace app\widgets\score;

use yii\base\Widget;

/**
 * Class ScoreWidget
 * @package app\widgets\score
 */
class ScoreWidget extends Widget {

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init() {
		parent::init();
		ScoreWidgetAssets::register($this->getView());
	}

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 */
	public function run():string {
		return $this->render('score');
	}
}
