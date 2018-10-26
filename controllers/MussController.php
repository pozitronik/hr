<?php
declare(strict_types = 1);

namespace app\controllers;

use app\models\imports\MussRecord;
use Yii;
use yii\base\Response;
use yii\web\Controller;

/**
 * Class MussController
 * @package app/commands
 */
class MussController extends Controller {

	/**
	 * @return string|Response
	 */
	public function actionIndex($filename) {
		$muss = new MussRecord();

		$muss->importRecords(Yii::getAlias('@app/').$filename);
	}

}
