<?php
/** @noinspection ALL */
declare(strict_types = 1);

namespace app\controllers;

use app\helpers\Utils;
use app\models\references\refs\RefGroupTypes;
use app\models\references\refs\RefUserRoles;
use app\models\relations\RelUsersGroups;
use app\models\users\Users;
use app\models\users\UsersOptions;
use app\widgets\alert\Alert;
use app\widgets\alert\AlertModel;
use kartik\growl\Growl;
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

		$q = RelUsersGroups::getUserGroupId(3,4);
		Utils::log($q);

	}

	public function actionFlash() {
//		AlertPrototype::SuccessNotify();
		AlertModel::SuccessNotify();
		return $this->render('flash');

	}

}
