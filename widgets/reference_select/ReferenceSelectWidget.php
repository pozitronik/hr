<?php
declare(strict_types = 1);

namespace app\widgets\reference_select;

use yii\base\Widget;

/**
 * Class GroupSelectWidget
 * Пустой шаблон виджета. Для быстрого использования копипастим под нужным именем, заменяем все *ReferenceSelect* на нужное нам имя, и работаем
 * @package app\components\reference_select
 */
class ReferenceSelectWidget extends Widget {

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init() {
		parent::init();
		ReferenceSelectWidgetAssets::register($this->getView());
	}

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 */
	public function run():string {
		return $this->render('reference_select');
	}
}
