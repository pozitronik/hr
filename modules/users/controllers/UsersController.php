<?php
declare(strict_types = 1);

namespace app\modules\users\controllers;

use app\models\core\WigetableController;
use app\modules\users\models\UsersSearch;
use Throwable;
use Yii;
use app\modules\users\models\Users;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
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
	public $defaultRoute = 'users/users';

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
	 * Макро обновления данных юзера
	 * @param Users $user
	 * @throws InvalidConfigException
	 * @throws Throwable
	 */
	private static function tryUpdate(Users $user):void {
		if ((null !== ($updateArray = Yii::$app->request->post($user->formName()))) && $user->updateModel($updateArray)) $user->uploadAvatar();
	}

	/**
	 * Основной список пользователей
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
	 * @param int $id
	 * @return string|null
	 * @throws Throwable
	 */
	public function actionProfile(int $id):?string {
		if (null === $user = Users::findModel($id, new NotFoundHttpException())) return null;
		self::tryUpdate($user);

		return $this->render('profile', [
			'model' => $user
		]);
	}

	/**
	 * @param int $id
	 * @return string|null
	 * @throws Throwable
	 */
	public function actionGroups(int $id):?string {
		if (null === $user = Users::findModel($id, new NotFoundHttpException())) return null;
		self::tryUpdate($user);
		return $this->render('groups', [
			'model' => $user,
			'provider' => new ActiveDataProvider(['query' => $user->getRelGroups()->orderBy('name')->active()])
		]);
	}

	/**
	 * @param int $id
	 * @return Response
	 */
	public function actionAttributes(int $id):Response {
		return $this->redirect(['/attributes/user', 'user_id' => $id]);
	}

	/**
	 * @param int $id
	 * @return string|null
	 * @throws Throwable
	 * @throws InvalidConfigException
	 */
	public function actionSalary(int $id):?string {
		if (null === $user = Users::findModel($id, new NotFoundHttpException())) return null;
		self::tryUpdate($user);
		return $this->render('salary', [
			'model' => $user
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
			return $this->redirect(['profile', 'id' => $newUser->id]);
		}

		return $this->render('profile', [
			'model' => $newUser
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
