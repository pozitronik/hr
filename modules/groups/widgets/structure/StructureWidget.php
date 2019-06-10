<?php
declare(strict_types = 1);

namespace app\modules\groups\widgets\structure;

use yii\base\Widget;

/**
 * Class StructureWidget
 * @package app\modules\groups\widgets\structure
 */
class StructureWidget extends Widget {

	public $id;

	public function init() {
		parent::init();
		StructureWidgetAssets::register($this->getView());
	}

	/**
	 * Функция возврата результата рендеринга виджета
	 * @return string
	 */
	public function run():string {
		StructureWidgetAssets::register($this->getView());
		return $this->render('tree', [
			'id' => $this->id
		]);

	}
}
