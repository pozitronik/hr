<?php
declare(strict_types = 1);

namespace app\controllers;

use app\helpers\ArrayHelper;
use app\helpers\Utils;
use app\models\dynamic_attributes\DynamicAttributes;
use app\models\groups\Groups;
use app\models\references\refs\RefGroupTypes;
use app\models\references\refs\RefUserRoles;
use app\models\relations\RelUsersGroups;
use app\models\users\Users;
use app\models\users\UsersOptions;
use app\widgets\alert\Alert;
use app\widgets\alert\AlertModel;
use kartik\growl\Growl;
use yii\base\Response;
use yii\data\ActiveDataProvider;
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
		return $this->render('index');

	}

	/**
	 * @return string|null
	 * @throws \Throwable
	 */
	public function actionUser() {
		if (null === $user = Users::findModel(1)) return null;



		return $this->render('/admin/users/attributes/test_widgets', [
			'user' => $user,
		]);
	}

}
