<?php
declare(strict_types = 1);

namespace app\controllers\service;

use app\helpers\ArrayHelper;
use app\models\core\WigetableController;
use yii\base\InlineAction;
use yii\base\Response;

/**
 * Class ServiceController
 * @package app\controllers\service
 */
class ServiceController extends WigetableController {

	private $controllers;

	/**
	 * @return string|Response
	 */
	public function actionIndex() {
		return $this->render('index');
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
