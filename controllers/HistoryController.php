<?php
declare(strict_types = 1);

namespace app\controllers;

use app\models\core\ActiveRecordLogger;
use app\models\core\Magic;
use app\models\core\WigetableController;
use app\models\prototypes\ModelHistory;
use ReflectionException;

/**
 * Class HistoryController
 * @package app\controllers
 */
class HistoryController extends WigetableController {

	/**
	 * @param string $className
	 * @param int $modelId
	 * @return string
	 */
	public function actionShow(string $className, int $modelId):string {
		if (null === $askedClass = Magic::LoadClassByName($className)) throw new ReflectionException("Class $className not found");
		$askedModel = $askedClass::findModel($modelId);

		$history = new ModelHistory(['requestModel' => $askedModel, 'loggerModel' => ActiveRecordLogger::class]);
		$timeline = $history->getHistory();
		$populatedTimeline = $history->populateTimeline($timeline);

		return $this->render('timeline', [
			'timeline' => $populatedTimeline
		]);
	}

}