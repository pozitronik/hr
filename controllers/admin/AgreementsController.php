<?php
declare(strict_types = 1);

namespace app\controllers\admin;

use pozitronik\core\models\core_controller\WigetableController;

/**
 * Class WorkgroupsController
 */
class AgreementsController extends WigetableController {
	public $menuCaption = "Договорённости";
	public $menuIcon = "/img/admin/agreements.png";
	public $menuDisabled = true;

	public function actionIndex():void {
		echo $this->id;
		echo "this is index";
	}
}
