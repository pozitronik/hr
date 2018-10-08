<?php
declare(strict_types = 1);

namespace app\controllers;

use app\models\core\WigetableController;
use ReflectionException;
use yii\base\Response;
use yii\base\UnknownClassException;
use yii\web\Controller;

/**
 * Class AdminController
 * @package app\controllers
 */
class AdminController extends Controller {

	public const CONTROLLERS_DIRECTORY = '@app/controllers/admin/';

	/**
	 * @return string|Response
	 * @throws ReflectionException
	 * @throws UnknownClassException
	 */
	public function actionIndex() {
		return $this->render('index', [
			'controllers' =>  WigetableController::GetControllersList(self::CONTROLLERS_DIRECTORY)
		]);
	}


}
