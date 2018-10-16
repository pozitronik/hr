<?php
declare(strict_types = 1);

namespace app\controllers\admin\users;

use app\models\core\WigetableController;
use app\models\users\UsersSearch;
use Throwable;
use Yii;
use app\helpers\ArrayHelper;
use app\models\users\Users;
use yii\web\ErrorAction;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class UsersController
 */
class UsersController extends WigetableController {
	public $menuCaption = "Пользователи";
	public $menuIcon = "/img/admin/users.png";

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
	 */
	public function actionIndex():string {
		$params = Yii::$app->request->queryParams;
		$searchModel = new UsersSearch();
		$allowedGroups = [];
		//Проверяем доступы к списку юзеров

		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $searchModel->search($params, $allowedGroups)
		]);
	}

	/**
	 * @return string|Response
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
	 * @return string
	 * @throws Throwable
	 */
	public function actionUpdate(int $id):string {
		$user = Users::findModel($id, new NotFoundHttpException());

		if (null !== ($updateArray = ArrayHelper::getValue(Yii::$app->request->post(), $user->classNameShort))) {
			$user->updateUser($updateArray);
		}
		return $this->render('update', [
			'model' => $user
		]);
	}

	/**
	 * @param int $id
	 * @throws Throwable
	 */
	public function actionDelete(int $id) {
		Users::findModel($id, new NotFoundHttpException())->safeDelete();
		$this->redirect('index');
	}
}
