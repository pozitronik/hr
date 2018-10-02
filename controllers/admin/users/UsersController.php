<?php
declare(strict_types=1);

namespace app\controllers\admin\users;

use Throwable;
use Yii;
use app\helpers\ArrayHelper;
use app\models\users\Users;
use yii\web\Controller;
use yii\web\ErrorAction;
use yii\web\NotFoundHttpException;

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
		if ($newUser->createUser(ArrayHelper::getValue(Yii::$app->request->post(), $newUser->classNameShort))) {
			return $this->redirect(['update', 'id' => $newUser->id]);
		}

		return $this->render('create', [
			'model' => $newUser
		]);
	}

	/**
	 * @param integer $id
	 */
	public function actionUpdate(int $id) {
		$user = Users::findModel($id, new NotFoundHttpException());

		if (null !== ($updateArray = ArrayHelper::getValue(Yii::$app->request->post(), $user->classNameShort))) {
			$user->updateUser($updateArray);
		}
		return $this->render('update', [
			'model' => $user
		]);
	}
}
