<?php
declare(strict_types = 1);

namespace app\modules\history\controllers;

use app\models\core\ActiveRecordLogger;
use app\models\core\Magic;
use app\models\core\WigetableController;
use app\modules\history\models\ModelHistory;
use ReflectionException;
use Throwable;
use yii\base\InvalidConfigException;
use yii\base\UnknownClassException;

/**
 * Class HistoryController
 * @package app\controllers
 */
class HistoryController extends WigetableController {

	/**
	 * @param string $className
	 * @param int $modelId
	 * @return string
	 * @throws ReflectionException
	 * @throws Throwable
	 * @throws InvalidConfigException
	 * @throws UnknownClassException
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