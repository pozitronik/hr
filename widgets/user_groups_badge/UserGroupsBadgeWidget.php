<?php
declare(strict_types = 1);

namespace app\widgets\user_groups_badge;

use yii\base\Widget;

/**
 * Class GroupSelectWidget
 * Пустой шаблон виджета. Для быстрого использования копипастим под нужным именем, заменяем все *UserGroupsBadge* на нужное нам имя, и работаем
 * @package app\components\user_groups_badge
 */
class UserGroupsBadgeWidget extends Widget {

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init() {
		parent::init();
		UserGroupsBadgeWidgetAssets::register($this->getView());
	}

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 */
	public function run():string {
		return $this->render('user_groups_badge');
	}
}
