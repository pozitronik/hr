<?php
declare(strict_types = 1);

namespace app\controllers;

use app\models\imports\FosRecord;
use Yii;
use yii\db\Exception;
use yii\web\Controller;

/**
 * Class ImportController
 * @package app\controllers
 */
class ImportController extends Controller {

	/**
	 * @param string $filename
	 * @throws Exception
	 */
	public function actionIndex($filename):void {
		$muss = new FosRecord();
		$muss->importRecords(Yii::getAlias('@app/').$filename);
	}

}
