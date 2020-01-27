<?php
declare(strict_types = 1);

namespace app\modules\groups\widgets\group_select;

use app\modules\groups\models\Groups;
use app\widgets\select_model\SelectModelWidget;

/**
 * Class GroupSelectWidget
 * @package app\modules\groups\widgets\group_select
 *
 * @property bool $groupByType -- группировка по типу данных
 */
class GroupSelectWidget extends SelectModelWidget {
	public $selectModel = Groups::class;
	public $ajaxSearchUrl = '/groups/ajax/group-search';//todo: динамическая ссылка
	public $groupByType = true;//todo: Добавить обработчик сюда, либо доопределить в базовой модели

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init() {
		parent::init();
		GroupSelectWidgetAssets::register($this->getView());
	}


}
