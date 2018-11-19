<?php
declare(strict_types = 1);

namespace app\controllers\admin;

use app\models\competencies\Competencies;
use app\models\core\WigetableController;
use app\models\users\UsersSearch;
use Throwable;
use Yii;
use app\models\users\Users;
use yii\data\ArrayDataProvider;
use yii\filters\ContentNegotiator;
use yii\web\ErrorAction;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class UsersController
 */
class UsersController extends WigetableController {
	public $menuCaption = "Люди";
	public $menuIcon = "/img/admin/users.png";

	/**
	 * @inheritdoc
	 */
	public function behaviors():array {
		return [
			[
				'class' => ContentNegotiator::class,
				'formats' => [
					'application/json' => Response::FORMAT_JSON,
					'application/xml' => Response::FORMAT_XML,
					'text/html' => Response::FORMAT_HTML
				]
			]
		];
	}

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
		if ($newUser->createUser(Yii::$app->request->post($newUser->classNameShort))) {
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
	 * @return string
	 * @throws Throwable
	 */
	public function actionUpdate(int $id):string {
		$user = Users::findModel($id, new NotFoundHttpException());

		if ((null !== ($updateArray = Yii::$app->request->post($user->classNameShort))) && $user->updateUser($updateArray)) $user->uploadAvatar();

		return $this->render('update', [
			'model' => $user
		]);
	}

	/**
	 * @param int $id
	 * @throws Throwable
	 */
	public function actionDelete(int $id):void {
		Users::findModel($id, new NotFoundHttpException())->safeDelete();
		$this->redirect('index');
	}

	/**
	 * Список компетенций пользователя
	 * @param int $id
	 * @return string
	 */
	public function actionCompetencies(int $id) {
		$user = Users::findModel($id, new NotFoundHttpException());
		$competencies = $user->relCompetencies;

		return $this->render('competencies/index', [
			'user' => $user,
			'dataProvider' => new ArrayDataProvider([
				'allModels' => $competencies
			])
		]);
	}

	/**
	 * Контроллер создания новой компетенции у пользователя
	 * @param int $user_id
	 * @return string|Response
	 * @throws Throwable
	 */
	public function actionCompetencyCreate(int $user_id) {
		$user = Users::findModel($user_id, new NotFoundHttpException());
		$competency = new Competencies();

		if ($competency->load(Yii::$app->request->post) && $competency->save()) {
			return $this->redirect(['competencies', 'id' => $user_id]);
		}

		return $this->render('competencies/create', [
			'user' => $user,
			'competency' => $competency
		]);
	}

	/**
	 * Контроллер изменения компетенции пользователя
	 * @param int $user_id
	 * @param int $competency_id
	 * @return string|Response
	 * @throws Throwable
	 */
	public function actionCompetencyUpdate(int $user_id, int $competency_id) {
		$user = Users::findModel($user_id, new NotFoundHttpException());
		$competency = Competencies::findModel($competency_id, new NotFoundHttpException());

		if ($competency->load(Yii::$app->request->post) && $competency->save()) {
			return $this->redirect(['competencies', 'id' => $user_id]);
		}

		return $this->render('competencies/update', [
			'user' => $user,
			'competency' => $competency
		]);
	}
}
