<?php
declare(strict_types = 1);

namespace app\modules\grades\controllers;

use app\models\core\WigetableController;

/**
 * Class GradesController
 * @package app\modules\grades\controllers
 */
class GradesController extends WigetableController {
	public $menuCaption = "<i class='fa fa-graduation-cap'></i>Грейды";
	public $menuIcon = "/img/admin/grades.png";
	public $orderWeight = 7;
	public $defaultRoute = 'grades/grades';

	public function actionIndex() {
		return $this->render('index');
	}

}