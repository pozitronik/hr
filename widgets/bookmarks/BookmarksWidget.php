<?php
declare(strict_types = 1);

namespace app\widgets\bookmarks;

use yii\base\Widget;

/**
 * Class GroupSelectWidget
 * Пустой шаблон виджета. Для быстрого использования копипастим под нужным именем, заменяем все *Bookmarks* на нужное нам имя, и работаем
 * @package app\components\bookmarks
 */
class BookmarksWidget extends Widget {

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init() {
		parent::init();
		BookmarksWidgetAssets::register($this->getView());
	}

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 */
	public function run():string {
		return $this->render('bookmarks');
	}
}
