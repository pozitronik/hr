<?php
declare(strict_types = 1);

namespace app\controllers\admin;

use app\models\competencies\CompetenciesSearch;
use app\models\core\WigetableController;
use yii\web\ErrorAction;
use Yii;

class CompetenciesController extends WigetableController {
	public $menuCaption = "Компетенции";
	public $menuIcon = "/img/admin/competency.png";
	public $disabled = false;
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

	public function actionIndex():string {
		$params = Yii::$app->request->queryParams;
		$searchModel = new CompetenciesSearch();

		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $searchModel->search($params)
		]);
	}
}
