<?php
declare(strict_types = 1);

namespace app\modules\references\widgets\reference_select;

use yii\base\Widget;

/**
 * Class GroupSelectWidget
 * TODO на реализацию: виджет (либо самостоятельный, либо расширяющий select2) с контролами быстрого едитинга связанного справочника
 * @package app\components\reference_select
 */
class BaseReferenceSelectWidget extends Widget {


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
