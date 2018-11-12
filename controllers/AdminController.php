<?php
declare(strict_types = 1);

namespace app\controllers;

use app\helpers\ArrayHelper;
use app\models\core\WigetableController;
use yii\base\InlineAction;
use yii\base\Response;
use yii\web\Controller;

/**
 * Class AdminController
 * @package app\controllers
 */
class AdminController extends Controller {

	public const CONTROLLERS_DIRECTORY = '@app/controllers/admin/';
	private $controllers;

	/**
	 * @return string|Response
	 */
	public function actionIndex() {
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
		if ((null === $action = parent::createAction($id)) && in_array("admin/$id", ArrayHelper::getColumn($this->controllers, 'id'))) {
			$this->redirect(["admin/$id/index"]);
			return new InlineAction($id, $this, 'actionIndex');//Можно вернуть пофиг что, но что-то корректное
		}
		return $action;
	}

}
