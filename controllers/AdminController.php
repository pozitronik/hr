<?php
declare(strict_types = 1);

namespace app\controllers;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionException;
use Yii;
use yii\base\Response;
use yii\base\UnknownClassException;
use yii\web\Controller;
use app\models\core\Magic;

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
			'controllers' => $this->GetAdminControllersList()
		]);
	}

	/**
	 * Выгружает список контроллеров в указанном неймспейсе
	 * @return Controller[]
	 * @throws ReflectionException
	 * @throws UnknownClassException
	 */
	private function GetAdminControllersList():array {
		$result = [];

		$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(Yii::getAlias(self::CONTROLLERS_DIRECTORY)), RecursiveIteratorIterator::SELF_FIRST);
		/** @var RecursiveDirectoryIterator $file */
		foreach ($files as $file) {
			if ($file->isFile() && 'php' === $file->getExtension() && false !== $controller = Magic::GetController($file->getRealPath())) {
				$result[] = $controller;
			}
		}
		return $result;
	}

}
