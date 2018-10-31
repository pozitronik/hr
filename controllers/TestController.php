<?php
/** @noinspection ALL */
declare(strict_types = 1);

namespace app\controllers;

use app\models\users\Users;
use app\models\users\UsersOptions;
use yii\base\Response;
use yii\web\Controller;

/**
 * Class TestController
 * @package app\controllers
 */
class TestController extends Controller {

	/**
	 * @return string|Response
	 */
	public function actionIndex() {
		$user = Users::findModel(1);
		return json_encode($user->options->get('test'));
	}

}
