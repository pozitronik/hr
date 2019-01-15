<?php
declare(strict_types = 1);

namespace app\controllers\service;

use app\models\core\Service;
use app\models\core\WigetableController;
use Yii;
use yii\base\Response;

/**
 * Class ServiceController
 * @package app\controllers\service
 */
class ServiceController extends WigetableController {

	/**
	 * @return string|Response
	 */
	public function actionIndex() {
		return $this->render('index');
	}

	/**
	 * @return string
	 */
	public function actionReset():string {
//		Yii::$app->user->logout();
		return $this->render('reset', [
			'result' =>  Service::ResetDB()
		]);

	}
}
