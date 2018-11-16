<?php
declare(strict_types = 1);

namespace app\controllers\admin;

use app\models\competencies\Competencies;
use app\models\competencies\CompetenciesSearch;
use app\models\core\WigetableController;
use yii\web\ErrorAction;
use Yii;

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
	 * @return string
	 */
	public function actionCreate():string {
		$newCompetency = new Competencies();

		return $this->render('create', [
			'model' => $newCompetency
		]);
	}
}
