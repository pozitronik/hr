<?php
declare(strict_types = 1);

namespace app\controllers\admin;

use app\models\competencies\Competencies;
use app\models\competencies\CompetenciesSearch;
use app\models\competencies\CompetencyField;
use app\models\core\WigetableController;
use app\models\prototypes\CompetenciesSearchItem;
use app\models\prototypes\CompetenciesSearchCollection;
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
			if (Yii::$app->request->post('more', false)) return $this->redirect('create');//Создали и создаём ещё
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
	 * Сохранение/изменение поля компетенции
	 * @param int $competency_id id компетенции
	 * @param null|int $field_id id поля (null для нового)
	 * @return string|Response
	 * @throws Throwable
	 */
	public function actionField(int $competency_id, $field_id = null) {
		$competency = Competencies::findModel($competency_id, new NotFoundHttpException());
		$field = new CompetencyField([
			'competencyId' => $competency_id
		]);
		if ($field->load(Yii::$app->request->post())) {
			$competency->setField($field, $field_id);
			if (Yii::$app->request->post('more', false)) return $this->redirect(['field', 'competency_id' => $competency_id, 'field_id' => $field_id]);//Создали и создаём ещё
			return $this->redirect(['update', 'id' => $competency_id]);

		}

		if (null === $field_id) {
			return $this->render('field/create', [
				'competency' => $competency,
				'model' => $field
			]);
		}

		$field = $competency->getFieldById((int)$field_id, new NotFoundHttpException());
		return $this->render('field/update', [
			'competency' => $competency,
			'model' => $field
		]);
	}

	/**
	 * @return string
	 */
	public function actionSearch():string {
		$searchSet = new CompetenciesSearchCollection();
		$competencies = Competencies::find()->active()->all();
		$competency_data = [];
		foreach ($competencies as $competency) {
			$competency_data[$competency->categoryName][$competency->id] = $competency->name;
		}
		$searchSet->load(Yii::$app->request->post());
		if (null !== Yii::$app->request->post('add')) {/*Нажали кнопку "добавить поле", догенерируем набор условий*/
			$searchSet->addItem(new CompetenciesSearchItem());
		} else {/*Нажали поиск, нужно сгенерировать запрос, поискать, отдать результат*/
			$searchCondition = $searchSet->searchCondition();
			return $this->render('search', [
				'model' => $searchSet,
				'dataProvider' => $searchCondition,
				'competency_data' => $competency_data,
			]);
		}

		return $this->render('search', [
			'model' => $searchSet,
			'dataProvider' => null,
			'competency_data' => $competency_data
		]);
	}
}
