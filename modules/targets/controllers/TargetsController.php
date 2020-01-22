<?php
declare(strict_types = 1);

namespace app\modules\targets\controllers;

use app\models\core\WigetableController;
use app\modules\targets\models\TargetsSearch;
use Yii;

/**
 * Class TargetsController
 * @package app\modules\targets\controllers
 */
class TargetsController extends WigetableController {
	public $menuCaption = "<i class='fa fa-arrow-circle-left'></i>Целеполагание";
	public $menuIcon = "/img/admin/privileges.png";
	public $menuDisabled = false;
	public $orderWeight = 15;
	public $defaultRoute = 'targets/targets';

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