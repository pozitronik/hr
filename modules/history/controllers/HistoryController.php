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
	public $menuDisabled = true;

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
		if (null === $askedClass = Magic::LoadClassByName($for)) throw new ReflectionException("Class $for not found");
		$askedModel = $askedClass::findModel($id);

		$history = new ModelHistory(['requestModel' => $askedModel, 'loggerModel' => ActiveRecordLogger::class]);
		$timeline = $history->getHistory();
		$populatedTimeline = $history->populateTimeline($timeline);

		return $this->render('timeline', [
			'timeline' => $populatedTimeline
		]);
	}

}