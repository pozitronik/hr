<?php
declare(strict_types = 1);

namespace app\models\core;

use app\helpers\ArrayHelper;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionException;
use Yii;
use yii\base\UnknownClassException;
use yii\web\Controller;

/**
 * Class WigetableController
 * Расширенный класс контроллера с дополнительными опциями встройки в меню и навигацию
 *
 * @property-read false|string $menuIcon
 * @property-read false|string $menuCaption
 * @property-read boolean $disabled
 * @property-read integer $orderWeight
 */
class WigetableController extends Controller {
	public $disabled = false;
	public $orderWeight = 0;
	/**
	 * Возвращает путь к иконке контроллера
	 * @return false|string
	 */
	public function getMenuIcon() {
		return false;
	}

	/**
	 * Возвращает строковое название пункта меню контроллера
	 * @return false|string
	 */
	public function getMenuCaption() {
		return false;
	}

	/**
	 * Выгружает список контроллеров в указанном неймспейсе
	 * @param string $path
	 * @return Controller[]
	 * @throws ReflectionException
	 * @throws UnknownClassException
	 */
	public static function GetControllersList($path):array {
		$result = [];

		$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(Yii::getAlias($path)), RecursiveIteratorIterator::SELF_FIRST);
		/** @var RecursiveDirectoryIterator $file */
		foreach ($files as $file) {
			if ($file->isFile() && 'php' === $file->getExtension() && null !== $controller = Magic::GetController($file->getRealPath())) {
				$result[] = $controller;
			}
		}
		ArrayHelper::multisort($result, ['orderWeight']);
		return $result;
	}
}