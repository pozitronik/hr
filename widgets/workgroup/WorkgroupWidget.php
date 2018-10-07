<?php
declare(strict_types = 1);

namespace app\widgets\workgroup;

use yii\base\Widget;

/**
 * Class WorkgroupWidget
 * Пустой шаблон виджета. Для быстрого использования копипастим под нужным именем, заменяем все *Workgroup* на нужное нам имя, и работаем
 * @package app\components\workgroup
 */
class WorkgroupWidget extends Widget {
	public $workgroup;
	public $user;

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init() {
		parent::init();
		WorkgroupWidgetAssets::register($this->getView());
	}

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 */
	public function run():string {
		return $this->render('workgroup', [
			'workgroup' => $this->workgroup,
			'user' => $this->user
		]);
	}
}
