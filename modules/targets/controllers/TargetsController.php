<?php
declare(strict_types = 1);

namespace app\modules\targets\controllers;

use app\modules\targets\models\TargetsSearch;
use Yii;
use yii\base\Controller;

/**
 * Class TargetsController
 * @package app\modules\targets\controllers
 */
class TargetsController extends Controller {

	/**
	 * @return string
	 */
	public function actionIndex():string {
		$params = Yii::$app->request->queryParams;
		$searchModel = new TargetsSearch();

		$dataProvider = $searchModel->search($params);

		return $this->render('index', compact('searchModel', 'dataProvider'));

	}
}