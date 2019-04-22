<?php
declare(strict_types = 1);

namespace app\modules\vacancy\controllers;

use app\models\core\WigetableController;
use app\modules\vacancy\models\VacancySearch;
use Throwable;
use Yii;

/**
 * Class VacancyController
 * @package app\modules\vacancy\controllers
 */
class VacancyController extends WigetableController {
	public $menuDisabled = false;
	public $orderWeight = 11;
	public $menuCaption = "<i class='fa fa-pray'></i>Подбор персонала";
	public $defaultRoute = 'vacancy/vacancy';

	/**
	 * @return string
	 * @throws Throwable
	 */
	public function actionIndex():string {
		$params = Yii::$app->request->queryParams;
		$searchModel = new VacancySearch();

		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $searchModel->search($params)
		]);

	}
}