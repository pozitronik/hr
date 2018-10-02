<?php
declare(strict_types=1);

namespace app\controllers\admin\users;

use Throwable;
use Yii;
use app\helpers\ArrayHelper;
use app\models\users\Users;
use yii\web\Controller;
use yii\web\ErrorAction;

/**
 * Class UsersController
 */
class UsersController extends Controller {

	/**
	 * @inheritdoc
	 */
	public function actions() {
		return [
			'error' => [
				'class' => ErrorAction::class
			]
		];
	}

	/**
	 * @return string
	 * @throws Throwable
	 */
	public function actionCreate() {
		$newUser = new Users();

		return $this->render('create', [
			'success' => $newUser::createUser(ArrayHelper::getValue(Yii::$app->request->post(), $newUser->classNameShort)),
			'model' => $newUser
		]);

	}
}
