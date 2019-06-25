<?php
declare(strict_types = 1);

namespace app\widgets\group_card;

use yii\base\Widget;

/**
 * Class GroupSelectWidget
 * Пустой шаблон виджета. Для быстрого использования копипастим под нужным именем, заменяем все *GroupCard* на нужное нам имя, и работаем
 * @package app\components\group_card
 */
class GroupCardWidget extends Widget {
	public $title;
	public $leader;
	public $logo;

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init() {
		parent::init();
		GroupCardWidgetAssets::register($this->getView());
	}

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 */
	public function run():string {
		return $this->render('group_card',[
			'title' => $this->title,
			'logo' => $this->logo,
			'leader' => ''
		]);
	}
}
