<?php
declare(strict_types = 1);

namespace app\modules\history\controllers;

use pozitronik\core\models\core_controller\WigetableController;
use app\modules\history\models\ActiveRecordLogger;
use app\modules\history\models\ActiveRecordLoggerSearch;
use ReflectionException;
use Throwable;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\UnknownClassException;

/**
 * Class HistoryController
 * @package app\controllers
 */
class HistoryController extends WigetableController {
	public $menuDisabled = false;
	public $orderWeight = 10;
	public $menuCaption = "<i class='fa fa-history'></i>История";
	public $defaultRoute = 'history/history';

	/**
	 * @return string
	 * @throws Throwable
	 */
	public function actionIndex():string {
		$params = Yii::$app->request->queryParams;
		$searchModel = new ActiveRecordLoggerSearch();

		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $searchModel->search($params)
		]);

	}

	/**
	 * @param string $for
	 * @param int $id
	 * @return string
	 * @throws ReflectionException
	 * @throws Throwable
	 * @throws InvalidConfigException
	 * @throws UnknownClassException
	 */
	public function actionShow(string $for, int $id):string {
		$logger = new ActiveRecordLogger([
			'model' => $for
		]);

		return $this->render('timeline', [
			'timeline' => $logger->getHistory($id)
		]);
	}

}