<?php
declare(strict_types = 1);

namespace app\controllers;

use app\models\imports\old\CompetencyRecord;
use app\models\imports\old\SokolovRecord;
use Throwable;
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
	 * @throws Throwable
	 */
	public function actionIndex($filename):void {
		$fos = new SokolovRecord();
		$fos->importRecords(Yii::getAlias('@app/').$filename);
	}

	/**
	 * @param $filename
	 * @throws Exception
	 */
	public function actionCompetency($filename):void {
		$attribute = new CompetencyRecord();
		$attribute->importRecords(Yii::getAlias('@app/').$filename);
	}

}
