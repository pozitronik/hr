<?php
/** @noinspection ALL */
declare(strict_types = 1);

namespace app\controllers;

use app\helpers\Utils;
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

		Utils::log(Groups::find()->joinWith(['relRefUserRoles'])->where(['boss_flag' => true, 'rel_users_groups.user_id' => 1])->createCommand()->rawSql);
		Utils::log(Users::find()->joinWith('relRefUserRoles')->where(['ref_user_roles.boss_flag' => true])->createCommand()->rawSql);

	}

	public function actionFlash() {
//		AlertPrototype::SuccessNotify();
		AlertModel::SuccessNotify();
		return $this->render('flash');

	}

}
