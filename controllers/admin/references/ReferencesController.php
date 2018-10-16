<?php
declare(strict_types = 1);

namespace app\controllers\admin\references;

use app\models\core\WigetableController;
use app\models\references\Reference;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use Throwable;

/**
 * Управление всеми справочниками
 */
class ReferencesController extends WigetableController {
	public $menuCaption = "Справочники";
	public $menuIcon = "/img/admin/references.png";

	public const REFERENCES_DIRECTORY = '@app/models/references/refs';

	/**
	 * @inheritdoc
	 */
	public function behaviors() {
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
	 * @throws \yii\web\ServerErrorHttpException
	 */
	public function actionIndex($class = false) {
		if (!$class) {//list all reference models
			$dataProvider = new ArrayDataProvider([
				'allModels' => Reference::GetReferencesList(self::REFERENCES_DIRECTORY)
			]);
			return $this->render('list', [
				'dataProvider' => $dataProvider
			]);
		}

		$className = Reference::getReferenceClass($class);
		$dataProvider = new ActiveDataProvider([
			'query' => $className->search(Yii::$app->request->queryParams)
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
	 * @throws \yii\web\ServerErrorHttpException
	 */
	public function actionView($class, $id) {
		return $this->render('view', [
			'model' => Reference::getReferenceClass($class)::findModel($id, new NotFoundHttpException())
		]);
	}

	/**
	 * @param string $class
	 * @return mixed
	 * @throws \yii\web\ServerErrorHttpException
	 */
	public function actionCreate($class) {
		/** @var Reference $model */
		$model = Reference::getReferenceClass($class);
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['index', 'class' => $class]);
		}

		return $this->render('create', [
			'model' => $model
		]);
	}

	/**
	 * @param string $class
	 * @param integer $id
	 * @return mixed
	 * @throws Throwable
	 * @throws \yii\web\ServerErrorHttpException
	 */
	public function actionUpdate($class, $id) {
		/** @var Reference $model */
		$model = Reference::getReferenceClass($class)::findModel($id, new NotFoundHttpException());

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['view', 'id' => $model->id, 'class' => $class]);
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
	 * @throws \yii\web\ServerErrorHttpException
	 */
	public function actionDelete($class, $id) {
		/** @var Reference $model */
		$model = Reference::getReferenceClass($class)::findModel($id, new NotFoundHttpException());
		$model->safeDelete();
		return $this->redirect(['index', 'class' => $class]);
	}
}
