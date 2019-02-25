<?php
declare(strict_types = 1);

namespace app\modules\grades\controllers;

use app\models\core\WigetableController;
use yii\data\ArrayDataProvider;

/**
 * Class GradesController
 * @package app\modules\grades\controllers
 */
class GradesController extends WigetableController {
	public $menuCaption = "<i class='fa fa-graduation-cap'></i>Грейды";
	public $menuIcon = "/img/admin/grades.png";
	public $orderWeight = 7;
	public $defaultRoute = 'grades/grades';

	/**
	 * @return string
	 */
	public function actionIndex():string {
		$dataProvider = new ArrayDataProvider(['allModels' => [
			[
				'id' => 1,
				'username' => 'СОКОЛОВ Дмитрий Юрьевич',
				'position' => 'Управляющий директор-начальник управления',
				'grade' => '15',
				'premium_group' => 'ИТ',
				'location' => 'Москва',
				'min' => 512400,
				'mid' => 640500,
				'max' => 768600
			]
		]]);

		return $this->render('index', [
			'dataProvider' => $dataProvider
		]);
	}

}