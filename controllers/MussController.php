<?php
declare(strict_types = 1);

namespace app\controllers;

use app\models\imports\MussRecord;
use Yii;
use yii\db\Exception;
use yii\web\Controller;

/**
 * Class MussController
 * @package app/commands
 */
class MussController extends Controller {

	/**
	 * @param string $filename
	 * @throws Exception
	 */
	public function actionIndex($filename):void {
		$muss = new MussRecord();
		$muss->importRecords(Yii::getAlias('@app/').$filename);
	}

	public function actionLinkChapters(){
		$muss = new MussRecord();
		$muss->linkChapters();
	}

}
