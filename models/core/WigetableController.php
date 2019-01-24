<?php
declare(strict_types = 1);

namespace app\models\core;

use app\helpers\ArrayHelper;
use app\models\user_rights\UserAccess;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionException;
use Yii;
use yii\base\UnknownClassException;
use yii\filters\AccessControl;
use yii\filters\ContentNegotiator;
use yii\web\Controller;
use yii\web\Response;

/**
 * Class WigetableController
 * Расширенный класс контроллера с дополнительными опциями встройки в меню и навигацию
 *
 * @property-read false|string $menuIcon
 * @property-read false|string $menuCaption
 * @property-read boolean $disabled
 * @property-read integer $orderWeight
 * @property-read string $defaultRoute
 */
class WigetableController extends Controller {
	public $disabled = false;
	public $orderWeight = 0;

	/**
	 * {@inheritDoc}
	 */
	public function behaviors():array {
		return [
			[
				'class' => ContentNegotiator::class,
				'formats' => [
					'application/json' => Response::FORMAT_JSON,
					'application/xml' => Response::FORMAT_XML,
					'text/html' => Response::FORMAT_HTML
				]
			],
			'access' => [
				'class' => AccessControl::class,
				'rules' => UserAccess::getUserAccessRules($this)
			]
		];
	}

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
	public static function GetControllersList(string $path):array {
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

	/**
	 * При необходимости здесь можно переопределить роут контроллера, обрабатываемый виджетом
	 * @return string
	 */
	public function getDefaultRoute():string {
		return $this->route;
	}
}