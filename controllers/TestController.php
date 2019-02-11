<?php
/** @noinspection ALL */
declare(strict_types = 1);

namespace app\controllers;

use app\helpers\ArrayHelper;
use app\helpers\Utils;
use app\modules\dynamic_attributes\models\DynamicAttributes;
use app\modules\groups\models\Groups;
use app\models\references\refs\RefGroupTypes;
use app\models\references\refs\RefUserRoles;
use app\models\relations\RelUsersGroups;
use app\modules\users\models\Users;
use app\modules\users\models\UsersOptions;
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


}
