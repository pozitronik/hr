<?php
declare(strict_types = 1);

namespace app\controllers\admin;

use pozitronik\core\models\core_controller\WigetableController;

/**
 * Class WorkgroupsController
 */
class TargetsController extends WigetableController {
	public $menuCaption = "Цели";
	public $menuIcon = "/img/admin/targets.png";
	public $menuDisabled = true;

	public function actionIndex():void {
		echo $this->id;
		echo "this is index";
	}
}
