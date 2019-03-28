<?php
declare(strict_types = 1);

namespace app\modules\privileges\controllers;

use app\models\core\core_module\PluginsSupport;
use app\models\core\Magic;
use app\models\core\WigetableController;
use app\modules\privileges\models\DynamicUserRights;
use Throwable;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Response;

/**
 * Class DynamicRightsController
 * @package app\modules\privileges\controllers
 */
class DynamicRightsController extends WigetableController {
	public $menuCaption = "<i class='fa fa-ruler'></i>Динамические правила";
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
		$ruleMap = [];
		$controllersPaths = PluginsSupport::GetAllControllersPaths();//Only WigetableControllers
		foreach ($controllersPaths as $moduleId => $controllerPath) {
			$controllers = WigetableController::GetControllersList($controllerPath, $moduleId);
			foreach ($controllers as $controller) {
				$ruleMap[$moduleId][$controller->id] = Magic::GetControllerActions($controller);
			}
		}

		return $this->render('create', [
			'model' => $newRight,
			'rules' => $ruleMap
		]);
	}
}