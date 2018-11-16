<?php
declare(strict_types = 1);

namespace app\controllers\admin;

use app\models\competencies\Competencies;
use app\models\competencies\CompetenciesSearch;
use app\models\competencies\CompetencyField;
use app\models\core\WigetableController;
use Throwable;
use yii\db\Exception;
use yii\web\ErrorAction;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class CompetenciesController
 * @package app\controllers\admin
 */
class CompetenciesController extends WigetableController {
	public $menuCaption = "Компетенции";
	public $menuIcon = "/img/admin/competency.png";

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
		$searchModel = new CompetenciesSearch();

		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $searchModel->search($params)
		]);
	}

	/**
	 * @return string|Response
	 * @throws Exception
	 */
	public function actionCreate() {
		$newCompetency = new Competencies();
		if ($newCompetency->createCompetency(Yii::$app->request->post($newCompetency->classNameShort))) {
			return $this->redirect(['update', 'id' => $newCompetency->id]);
		}
		return $this->render('create', [
			'model' => $newCompetency
		]);
	}

	/**
	 * @param int $id
	 * @return string
	 * @throws Throwable
	 */
	public function actionUpdate(int $id):string {
		$competency = Competencies::findModel($id, new NotFoundHttpException());

		if (null !== ($updateArray = Yii::$app->request->post($competency->classNameShort))) {
			$competency->updateCompetency($updateArray);
		}

		return $this->render('update', [
			'model' => $competency
		]);
	}

	/**
	 * @param int $id
	 * @throws Throwable
	 */
	public function actionDelete(int $id):void {
		Competencies::findModel($id, new NotFoundHttpException())->safeDelete();
		$this->redirect('index');
	}

	/**
	 * @param int $competency_id
	 * @param null|int $field_id
	 * @return string
	 * @throws Throwable
	 */
	public function actionField(int $competency_id, $field_id = null):string {
		$competency = Competencies::findModel($competency_id, new NotFoundHttpException());
		$field = new CompetencyField();
		if ($field->load(Yii::$app->request->post())) {
			$competency->setField($field, $field_id);

			return $this->render('update', [
				'model' => $competency
			]);
		}

		return $this->render('field/create', [
			'competency' => $competency
		]);
	}
}
