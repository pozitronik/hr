<?php
declare(strict_types = 1);

namespace app\modules\salary\controllers;

use app\models\core\WigetableController;
use app\modules\salary\models\SalaryFork;
use app\modules\salary\models\SalaryForkSearch;
use Throwable;
use Yii;
use yii\base\InvalidConfigException;
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
		$params = Yii::$app->request->queryParams;
		$searchModel = new SalaryForkSearch();

		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $searchModel->search($params)
		]);

	}

	/**
	 * Может принять набор параметров (все айдишники) для предзаполнения полей (например, при задании видки для уже существующей комбинации)
	 * @param int|null $position
	 * @param int|null $grade
	 * @param int|null $premium_group
	 * @param int|null $location
	 * @return string|Response
	 * @throws Exception
	 * @throws InvalidConfigException
	 */
	public function actionCreate(?int $position = null, ?int $grade = null, ?int $premium_group = null, ?int $location = null) {
		$newFork = new SalaryFork();
		if ($newFork->createModel(Yii::$app->request->post($newFork->formName()))) {
			if (Yii::$app->request->post('more', false)) return $this->redirect('create');//Создали и создаём ещё
			return $this->redirect(['update', 'id' => $newFork->id]);
		}

		$newFork->setAttributes([
			'position_id' => $position,
			'grade_id' => $grade,
			'premium_group_id' => $premium_group,
			'location_id' => $location
		]);

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