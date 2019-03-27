<?php
declare(strict_types = 1);

namespace app\controllers\admin;

use app\models\core\WigetableController;

/**
 * Class WorkgroupsController
 */
class AgreementsController extends WigetableController {
	public $menuCaption = "Договорённости";
	public $menuIcon = "/img/admin/agreements.png";
	public $disabled = true;

	public function actionIndex():void {
		echo $this->id;
		echo "this is index";
	}
}
