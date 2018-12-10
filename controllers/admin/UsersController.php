<?php
declare(strict_types = 1);

namespace app\controllers\admin;

use app\helpers\ArrayHelper;
use app\models\competencies\Competencies;
use app\models\core\WigetableController;
use app\models\users\UsersSearch;
use Throwable;
use Yii;
use app\models\users\Users;
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
		if ($newUser->createUser(Yii::$app->request->post($newUser->formName()))) {
			$newUser->uploadAvatar();
			if (Yii::$app->request->post('more', false)) return $this->redirect('create');//Создали и создаём ещё
			return $this->redirect(['update', 'id' => $newUser->id]);
		}

		return $this->render('create', [
			'model' => $newUser,
			'competenciesData' => ArrayHelper::map(Competencies::find()->active()->all(), 'id', 'name')
		]);
	}

	/**
	 * @param integer $id
	 * @return string|array
	 * @throws Throwable
	 */
	public function actionUpdate(int $id) {
		$user = Users::findModel($id, new NotFoundHttpException());

		if ((null !== ($updateArray = Yii::$app->request->post($user->formName()))) && $user->updateUser($updateArray)) $user->uploadAvatar();

		return $this->render('update', [
			'model' => $user,
			'competenciesData' => ArrayHelper::map(Competencies::find()->active()->where(['not in', 'id', ArrayHelper::getColumn($user->relCompetencies, 'id')])->all(), 'id', 'name')
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
	 * Редактор компетенций пользователя
	 * @param int $user_id
	 * @param int $competency_id
	 * @return string
	 * @throws Throwable
	 */
	public function actionCompetencies(int $user_id, int $competency_id):string {
		$user = Users::findModel($user_id, new NotFoundHttpException());
		$competency = Competencies::findModel($competency_id, new NotFoundHttpException());
		if (null !== $data = Yii::$app->request->post('CompetencyField')) {
			$competency->setUserFields($user_id, $data);
		}

		return $this->render('competencies', compact('user', 'competency'));
	}

}
