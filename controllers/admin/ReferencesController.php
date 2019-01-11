<?php
declare(strict_types = 1);

namespace app\controllers\admin;

use app\models\core\WigetableController;
use app\models\references\Reference;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use Throwable;
use yii\web\Response;
use yii\web\ServerErrorHttpException;

/**
 * Управление всеми справочниками
 */
class ReferencesController extends WigetableController {
	public $menuCaption = "<i class='fa fa-book'></i>Справочники";
	public $menuIcon = "/img/admin/references.png";
	public $orderWeight = 3;

	/**
	 * @inheritdoc
	 */
	public function behaviors():array {
		return [
			'access' => [
				'class' => AccessControl::class,
				'rules' => [
					[
						'actions' => ['index', 'create', 'view', 'update', 'delete'],
						'allow' => true,
						'roles' => ['@']
					]
				]
			],
			'verbs' => [
				'class' => VerbFilter::class,
				'actions' => [
					'delete' => ['post']
				]
			]
		];
	}

	/**
	 * @param string|false $class
	 * @return mixed
	 * @throws Throwable
	 * @throws ServerErrorHttpException
	 */
	public function actionIndex($class = false) {
		if (!$class) {//list all reference models
			$dataProvider = new ArrayDataProvider([
				'allModels' => Reference::GetReferencesList()
			]);
			return $this->render('list', [
				'dataProvider' => $dataProvider
			]);
		}

		$className = Reference::getReferenceClass($class);
		$dataProvider = new ActiveDataProvider([
			'query' => $className->search(Yii::$app->request->queryParams),
			'sort' => $className->searchSort
		]);

		return $this->render('index', [
			'searchModel' => $className,
			'dataProvider' => $dataProvider,
			'class' => $className
		]);
	}

	/**
	 * @param string $class
	 * @param integer $id
	 * @return mixed
	 * @throws Throwable
	 * @throws ServerErrorHttpException
	 * @unused
	 */
	public function actionView($class, $id) {
		return $this->render('view', [
			'model' => Reference::getReferenceClass($class)::findModel($id, new NotFoundHttpException())
		]);
	}

	/**
	 * @param string $class
	 * @return null|string|Response
	 * @throws ServerErrorHttpException
	 * @throws Throwable
	 */
	public function actionCreate($class) {
		if (null === $model = Reference::getReferenceClass($class)) return null;
		if ($model->createRecord(Yii::$app->request->post($model->formName()))) {
			if (Yii::$app->request->post('more', false)) return $this->redirect(['create', 'class' => $class]);//Создали и создаём ещё
			return $this->redirect(['index', 'class' => $class]);
		}

		return $this->render('create', [
			'model' => $model
		]);
	}

	/**
	 * @param string $class
	 * @param integer $id
	 * @return null|string|Response
	 * @throws Throwable
	 * @throws ServerErrorHttpException
	 */
	public function actionUpdate($class, $id) {
		if (null === $model = Reference::getReferenceClass($class)::findModel($id, new NotFoundHttpException())) return null;

		if ($model->updateRecord(Yii::$app->request->post($model->formName()))) {
			return $this->redirect(['update', 'id' => $model->id, 'class' => $class]);
		}

		return $this->render('update', [
			'model' => $model
		]);
	}

	/**
	 * @param string $class
	 * @param integer $id
	 * @return mixed
	 * @throws Throwable
	 * @throws ServerErrorHttpException
	 */
	public function actionDelete($class, $id) {
		if (null === $model = Reference::getReferenceClass($class)::findModel($id, new NotFoundHttpException())) $model->safeDelete();
		return $this->redirect(['index', 'class' => $class]);
	}
}
