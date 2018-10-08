<?php
declare(strict_types = 1);

namespace app\controllers\admin\competency;

use app\models\core\WigetableController;
use yii\web\ErrorAction;

/**
 * Class WorkgroupsController
 */
class CompetencyController extends WigetableController {
	public $menuCaption = "Компетенции";
	public $menuIcon = "/img/admin/competency.png";

	/**
	 * @inheritdoc
	 */
	public function actions() {
		return [
			'error' => [
				'class' => ErrorAction::class
			]
		];
	}

	public function actionIndex():void {
		echo $this->id;
		echo "this is index";
	}
}
