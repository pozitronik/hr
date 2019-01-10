<?php
declare(strict_types = 1);

namespace app\controllers\admin;

use app\models\core\WigetableController;
use app\models\user_rights\Privileges;
use Throwable;
use Yii;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\web\ErrorAction;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class RightsController
 * @package app\controllers\admin
 */
class PrivilegesController extends WigetableController {
	public $menuCaption = "<i class='fa fa-users-crown'></i>Привилегии";
	public $menuIcon = "/img/admin/privileges.png";
	public $disabled = false;
	public $orderWeight = 5;

	/**
	 * @inheritdoc
	 */
	public function actions() {
		return [
			'error' => [
				'class' => ErrorAction::class
			]
		];
	}

	/**
	 * @return string
	 */
	public function actionIndex():string {
		$dataProvider = new ActiveDataProvider([
			'query' => Privileges::find()->active()
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
		$newPrivilege = new Privileges();
		if ($newPrivilege->createPrivilege(Yii::$app->request->post($newPrivilege->formName()))) {
			if (Yii::$app->request->post('more', false)) return $this->redirect('create');//Создали и создаём ещё
			return $this->redirect(['update', 'id' => $newPrivilege->id]);
		}

		$userRightsProvider = new ArrayDataProvider([
			'allModels' => $newPrivilege->userRights
		]);

		return $this->render('create', [
			'model' => $newPrivilege,
			'userRights' => $userRightsProvider
		]);
	}

	/**
	 * @param int $id
	 * @return string
	 * @throws Throwable
	 * @throws InvalidConfigException
	 */
	public function actionUpdate(int $id):string {
		$privilege = Privileges::findModel($id, new NotFoundHttpException());

		if (null !== ($updateArray = Yii::$app->request->post($privilege->formName()))) $privilege->updatePrivilege($updateArray);
		$userRightsProvider = new ArrayDataProvider([
			'allModels' => $privilege->userRights
		]);

		return $this->render('update', [
			'model' => $privilege,
			'userRights' => $userRightsProvider
		]);
	}
}
