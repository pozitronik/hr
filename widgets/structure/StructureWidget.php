<?php
declare(strict_types = 1);

namespace app\widgets\structure;

use yii\base\Widget;

/**
 * Class GroupSelectWidget
 * Пустой шаблон виджета. Для быстрого использования копипастим под нужным именем, заменяем все *Structure* на нужное нам имя, и работаем
 * @package app\components\structure
 */
class StructureWidget extends Widget {
	public $id;

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init() {
		parent::init();
		StructureWidgetAssets::register($this->getView());
	}

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 */
	public function run():string {
		return $this->render('structure',[
			'id' => $this->id
		]);
	}
}
