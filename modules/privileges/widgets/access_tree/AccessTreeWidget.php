<?php
declare(strict_types = 1);

namespace app\modules\privileges\widgets\access_tree;

use yii\base\Widget;

/**
 * Class GroupSelectWidget
 * Пустой шаблон виджета. Для быстрого использования копипастим под нужным именем, заменяем все *AccessTree* на нужное нам имя, и работаем
 * @package app\components\access_tree
 */
class AccessTreeWidget extends Widget {

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init() {
		parent::init();
		AccessTreeWidgetAssets::register($this->getView());
	}

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 */
	public function run():string {
		return $this->render('access_tree');
	}
}
