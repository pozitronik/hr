<?php
declare(strict_types = 1);

namespace app\modules\salary\controllers;

use app\models\core\WigetableController;
use app\modules\salary\models\SalaryFork;
use Throwable;
use Yii;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class GradesController
 * @package app\modules\grades\controllers
 */
class SalaryController extends WigetableController {
	public $menuCaption = "<i class='fa fa-money-bill'></i>Зарплаты";
	public $menuIcon = "/img/admin/grades.png";
	public $orderWeight = 7;
	public $defaultRoute = 'salary/salary';

	/**
	 * @return string
	 */
	public function actionIndex():string {
		$dataProvider = new ActiveDataProvider([
			'query' => SalaryFork::find()->active()
		]);

		return $this->render('index', [
			'dataProvider' => $dataProvider
		]);
	}

	/**
	 * @return string|Response
	 * @throws InvalidConfigException
	 * @throws Exception
	 */
	public function actionCreate() {
		$newFork = new SalaryFork();
		if ($newFork->createModel(Yii::$app->request->post($newFork->formName()))) {
			if (Yii::$app->request->post('more', false)) return $this->redirect('create');//Создали и создаём ещё
			return $this->redirect(['update', 'id' => $newFork->id]);
		}


		return $this->render('create', [
			'model' => $newFork
		]);
	}

	/**
	 * @param int $id
	 * @return null|string
	 * @throws InvalidConfigException
	 * @throws Throwable
	 */
	public function actionUpdate(int $id):?string {
		if (null === $fork = SalaryFork::findModel($id, new NotFoundHttpException())) return null;

		if (null !== ($updateArray = Yii::$app->request->post($fork->formName()))) $fork->updateModel($updateArray);

		return $this->render('update', [
			'model' => $fork
		]);
	}

}