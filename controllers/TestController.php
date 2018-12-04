<?php
/** @noinspection ALL */
declare(strict_types = 1);

namespace app\controllers;

use app\models\prototypes\AlertPrototype;
use app\models\references\refs\RefGroupTypes;
use app\models\users\Users;
use app\models\users\UsersOptions;
use app\widgets\alert\Alert;
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
		RefGroupTypes::merge(5, 33);
	}

	public function actionFlash(){
		AlertPrototype::SuccessNotify();
		return $this->render('flash');

	}

}
