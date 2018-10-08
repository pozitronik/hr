<?php
declare(strict_types = 1);

namespace app\controllers\admin\wages;

use app\models\core\WigetableController;
use yii\web\ErrorAction;

/**
 * Class WorkgroupsController
 */
class WagesController extends WigetableController {
	public $menuCaption = "Зарплаты";
	public $menuIcon = "/img/admin/wages.png";

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
