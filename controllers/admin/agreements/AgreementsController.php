<?php
declare(strict_types = 1);

namespace app\controllers\admin\agreements;

use app\models\core\WigetableController;
use yii\web\ErrorAction;

/**
 * Class WorkgroupsController
 */
class AgreementsController extends WigetableController {
	public $menuCaption = "Договорённости";
	public $menuIcon = "/img/admin/agreements.png";

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
