<?php
declare(strict_types = 1);

namespace app\controllers\admin\targets;

use app\models\core\WigetableController;
use yii\web\ErrorAction;

/**
 * Class WorkgroupsController
 */
class TargetsController extends WigetableController {
	public $menuCaption = "Цели";
	public $menuIcon = "/img/admin/targets.png";
	public $disabled = true;
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
