<?php
declare(strict_types = 1);

namespace app\controllers\admin;

use app\helpers\ArrayHelper;
use app\models\competencies\Competencies;
use app\models\core\WigetableController;
use app\models\groups\Groups;
use app\models\users\UsersMassUpdate;
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

	/**
	 * Групповое изменение пользователей
	 * В post['selection'] приходят айдишники выбранных юзеров
	 * @param int|null $group_id - если указано, то выбираются пользователи этой группы
	 * @param bool $hierarchy вместе с group_id прогружает иерархично всех пользователей вниз
	 * @return string|Response
	 * @throws Throwable
	 */
	public function actionMassUpdate(int $group_id = null, bool $hierarchy = false) {
		$massUpdate = new UsersMassUpdate();

		if ($massUpdate->load(Yii::$app->request->post())) {
			$statistics = new ArrayDataProvider([
				'allModels' => $massUpdate->apply(),
				'sort' => [
					'attributes' => ['id', 'status', 'error']
				]
			]);
			$massUpdate->loadSelection($massUpdate->usersId);/*Переподгружаем список айдишников для перегенерации доступных наборов параметров*/
			return $this->render('mass-update', [
				'massUpdateModel' => $massUpdate,
				'statistics' => $statistics,
				'competenciesData' => ArrayHelper::map(Competencies::find()->active()->all(), 'id', 'name'),
				'group' => Groups::findModel($group_id)
			]);
		}

		if ((null !== $group_id) && false !== $massUpdate->loadGroupSelection($group_id, $hierarchy)) {
			return $this->render('mass-update', [
				'massUpdateModel' => $massUpdate,
				'statistics' => null,
				'competenciesData' => ArrayHelper::map(Competencies::find()->active()->all(), 'id', 'name'),
				'group' => Groups::findModel($group_id)
			]);
		}

		if (false !== $massUpdate->loadSelection(Yii::$app->request->post('selection'))) {
			return $this->render('mass-update', [
				'massUpdateModel' => $massUpdate,
				'statistics' => null,
				'competenciesData' => ArrayHelper::map(Competencies::find()->active()->all(), 'id', 'name'),
				'group' => Groups::findModel($group_id)
			]);
		}
		/*Никаких фильтрационных параметров не передали, редактим всех*/
		$massUpdate->usersId = ArrayHelper::getColumn(Users::find()->active()->all(), 'id');
		return $this->render('mass-update', [
			'massUpdateModel' => $massUpdate,
			'statistics' => null,
			'competenciesData' => ArrayHelper::map(Competencies::find()->active()->all(), 'id', 'name'),
			'group' => false
		]);
	}

}
