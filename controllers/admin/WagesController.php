<?php
declare(strict_types = 1);

namespace app\controllers\admin;

use app\models\core\controllers\WigetableController;

/**
 * Class WorkgroupsController
 */
class WagesController extends WigetableController {
	public $menuCaption = "Зарплаты";
	public $menuIcon = "/img/admin/wages.png";
	public $menuDisabled = true;

	/**
	 *
	 */
	public function actionIndex():void {
		echo $this->id;
		echo "this is index";
	}
}
