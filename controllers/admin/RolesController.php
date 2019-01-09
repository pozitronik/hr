<?php
declare(strict_types = 1);

namespace app\controllers\admin;

use app\models\core\WigetableController;
use yii\web\ErrorAction;

/**
 * Class RolesController
 * @package app\controllers\admin
 */
class RolesController extends WigetableController {
	public $menuCaption = "<i class='fa fa-users-crown'></i>Управление ролями";
	public $menuIcon = "/img/admin/roles.png";
	public $disabled = false;
	public $orderWeight = 5;
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
