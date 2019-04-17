<?php
declare(strict_types = 1);

namespace app\modules\history\controllers;

use app\models\core\WigetableController;
use app\modules\history\models\ActiveRecordLogger;
use app\modules\history\models\ActiveRecordLoggerSearch;
use app\modules\history\models\ModelHistory;
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
		$history = new ModelHistory(['loggerModel' => ActiveRecordLogger::class]);

		$timeline = $history->getHistory($for, $id);
		$populatedTimeline = $history->populateTimeline($timeline);

		return $this->render('timeline', [
			'timeline' => $populatedTimeline
		]);
	}

}