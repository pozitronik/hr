<?php
declare(strict_types = 1);

namespace app\controllers\admin;

use app\models\core\WigetableController;

/**
 * Class WorkgroupsController
 */
class TargetsController extends WigetableController {
	public $menuCaption = "Цели";
	public $menuIcon = "/img/admin/targets.png";
	public $disabled = true;

	public function actionIndex():void {
		echo $this->id;
		echo "this is index";
	}
}
