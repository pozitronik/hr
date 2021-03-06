<?php
declare(strict_types = 1);

namespace app\controllers;

use app\models\core\controllers\WigetableController;
use app\components\pozitronik\helpers\ArrayHelper;
use yii\base\InlineAction;
use yii\web\Controller;

/**
 * Class AdminController
 * @package app\controllers
 */
class AdminController extends Controller {

	public const CONTROLLERS_DIRECTORY = '@app/controllers/admin/';
	private $controllers;

	/**
	 * @return string
	 */
	public function actionIndex():string {
		return $this->render('index', [
			'controllers' => $this->controllers
		]);
	}

	/**
	 * @inheritdoc
	 */
	public function init():void {
		parent::init();
		$this->controllers = WigetableController::GetControllersList(self::CONTROLLERS_DIRECTORY);
	}

	/**
	 * @inheritdoc
	 */
	public function createAction($id) {
		if ((null === $action = parent::createAction($id)) && in_array("admin/$id", ArrayHelper::getColumn($this->controllers, 'id'), true)) {
			$this->redirect(["admin/$id/index"]);
			return new InlineAction($id, $this, 'actionIndex');//Можно вернуть пофиг что, но что-то корректное
		}
		return $action;
	}

}
