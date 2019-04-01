<?php
declare(strict_types = 1);

namespace app\modules\privileges\controllers;

use app\models\core\WigetableController;
use app\modules\privileges\models\DynamicUserRights;
use Throwable;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class DynamicRightsController
 * @package app\modules\privileges\controllers
 */
class DynamicRightsController extends WigetableController {
	public $menuCaption = "<i class='fa fa-ruler'></i>Правила доступа";
	public $menuIcon = "/img/admin/rules.png";
	public $menuDisabled = false;
	public $orderWeight = 6;
	public $defaultRoute = 'privileges/dynamic-rights';

	/**
	 * @return string
	 */
	public function actionIndex():string {
		$dataProvider = new ActiveDataProvider([
			'query' => DynamicUserRights::find()->active()
		]);

		return $this->render('index', [
			'dataProvider' => $dataProvider
		]);
	}

	/**
	 * @return string|Response
	 * @throws Throwable
	 */
	public function actionCreate() {
		$newRight = new DynamicUserRights();
		if ($newRight->createModel(Yii::$app->request->post($newRight->formName()))) {
			if (Yii::$app->request->post('more', false)) return $this->redirect('create');//Создали и создаём ещё
			return $this->redirect(['update', 'id' => $newRight->id]);
		}

		return $this->render('create', [
			'model' => $newRight
		]);
	}

	/**
	 * @param int $id
	 * @return null|string
	 * @throws Throwable
	 */
	public function actionUpdate(int $id):?string {
		if (null === $right = DynamicUserRights::findModel($id, new NotFoundHttpException())) return null;

		if (null !== ($updateArray = Yii::$app->request->post($right->formName()))) $right->updateModel($updateArray);

		return $this->render('update', [
			'model' => $right
		]);
	}
}