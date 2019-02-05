<?php
declare(strict_types = 1);

namespace app\controllers\admin;

use app\models\core\WigetableController;
use app\models\users\UsersSearch;
use Throwable;
use Yii;
use app\models\users\Users;
use yii\base\InvalidConfigException;
use yii\web\ErrorAction;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class UsersController
 */
class UsersController extends WigetableController {
	public $menuCaption = "<i class='fa fa-user'></i>Люди";
	public $menuIcon = "/img/admin/users.png";
	public $orderWeight = 1;

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
		if ($newUser->createModel(Yii::$app->request->post($newUser->formName()))) {
			$newUser->uploadAvatar();
			if (Yii::$app->request->post('more', false)) return $this->redirect('create');//Создали и создаём ещё
			return $this->redirect(['update', 'id' => $newUser->id]);
		}

		return $this->render('create', [
			'model' => $newUser
		]);
	}

	/**
	 * @param integer $id
	 * @return string|null
	 * @throws Throwable
	 * @throws InvalidConfigException
	 */
	public function actionUpdate(int $id):?string {
		if (null === $user = Users::findModel($id, new NotFoundHttpException())) return null;

		if ((null !== ($updateArray = Yii::$app->request->post($user->formName()))) && $user->updateModel($updateArray)) $user->uploadAvatar();

		return $this->render('update', [
			'model' => $user
		]);
	}

	/**
	 * @param int $id
	 * @return Response
	 * @throws Throwable
	 */
	public function actionDelete(int $id):Response {
		if (null !== $user = Users::findModel($id, new NotFoundHttpException())) $user->safeDelete();
		return $this->redirect('index');
	}

}
