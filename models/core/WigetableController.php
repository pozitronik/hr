<?php
declare(strict_types = 1);

namespace app\models\core;

use app\helpers\Path;
use app\models\user_rights\UserAccess;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;
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
	 * @param string $className
	 * @return string
	 * @todo: функция не умеет преобразовывать имена классов по той же схеме, что Yii. Реализован простейший вариант, а вот что-то вроде MassUpdateController к mass-update эта регулярка уже не вернёт.
	 */
	private static function ExtractControllerId(string $className):string {
		$id = mb_strtolower(preg_replace('/(^.+)([A-Z].+)(Controller$)/', '$2', $className));
		return "admin/{$id}";
	}

	/**
	 * Загружает динамически класс веб-контроллера Yii2 по его пути
	 * @param string $fileName
	 * @return Controller|null
	 * @throws ReflectionException
	 * @throws UnknownClassException
	 */
	public static function GetController(string $fileName):?object {
		$className = Magic::ExtractNamespaceFromFile($fileName).'\\'.Path::ChangeFileExtension($fileName);
		if (!class_exists($className)) Yii::autoload($className);
		$class = new ReflectionClass($className);
		if ($class->isSubclassOf(__CLASS__)) {
			return new $className(self::ExtractControllerId($className), Yii::$app);
		}
		return null;
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
			if ($file->isFile() && 'php' === $file->getExtension() && null !== $controller = self::GetController($file->getRealPath())) {
				$result[] = $controller;
			}
		}
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