<?php
declare(strict_types = 1);

namespace app\controllers\admin\workgroups;

use app\models\core\WigetableController;
use yii\web\ErrorAction;

/**
 * Class WorkgroupsController
 */
class WorkgroupsController extends WigetableController {
	public $menuCaption = "Команды";
	public $menuIcon = "/img/admin/workgroups.png";

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
