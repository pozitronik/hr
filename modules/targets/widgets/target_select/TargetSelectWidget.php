<?php
declare(strict_types = 1);

namespace app\modules\targets\widgets\target_select;

use app\modules\targets\models\Targets;
use app\modules\targets\TargetsModule;
use app\widgets\select_model\SelectModelWidget;

/**
 * Виджет выбора задачи целеполагания (общий, для тех моделей, которые имеют нужные атрибуты).
 * Может работать в двух режимах. MODE_FIELD - как поле ActiveForm. В этом случае виджет является просто выбиралкой.
 * MODE_FORM - самостоятельная форма, в этом случае виджет сам отрендерит форму с указанным экшоном.
 */
class TargetSelectWidget extends SelectModelWidget {
	public $selectModel = Targets::class;
	public $jsPrefix = 'Targets';

	/**
	 * Функция инициализации и нормализации свойств виджета
	 */
	public function init() {
		parent::init();
		TargetSelectWidgetAssets::register($this->getView());
		$this->ajaxSearchUrl = self::DATA_MODE_AJAX === $this->loadingMode?TargetsModule::to('ajax/target-search'):$this->ajaxSearchUrl;
	}

}
