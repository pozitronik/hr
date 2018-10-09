<?php
declare(strict_types = 1);

namespace app\controllers\admin\groups;

use app\models\core\WigetableController;
use yii\web\ErrorAction;

/**
 * Class WorkgroupsController
 */
class GroupsController extends WigetableController {
	public $menuCaption = "Команды";
	public $menuIcon = "/img/admin/groups.png";

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
