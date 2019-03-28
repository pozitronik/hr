<?php
declare(strict_types = 1);

namespace app\models\core;

use app\helpers\Path;
use app\models\core\core_module\PluginsSupport;
use app\modules\privileges\models\UserAccess;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;
use ReflectionException;
use Throwable;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\UnknownClassException;
use yii\filters\AccessControl;
use yii\filters\ContentNegotiator;
use yii\web\Controller;
use yii\web\ErrorAction;
use yii\web\Response;

/**
 * Class WigetableController
 * Расширенный класс контроллера с дополнительными опциями встройки в меню и навигацию
 *
 * @property-read false|string $menuIcon
 * @property-read false|string $menuCaption
 * @property-read boolean $menuDisabled
 * @property-read integer $orderWeight
 * @property-read string $defaultRoute
 */
class WigetableController extends Controller {
	public $menuDisabled = false;//отключает пункт меню
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
	 * @return array
	 */
	public function actions():array {
		return [
			'error' => [
				'class' => ErrorAction::class
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
	 * Вытаскивает из имени класса контроллера его id
	 * app/shit/BlaBlaBlaController => bla-bla-bla
	 * @param string $className
	 * @return string
	 */
	private static function ExtractControllerId(string $className):string {
		$controllerName = preg_replace('/(^.+)(\\\)([A-Z].+)(Controller$)/', '$3', $className);//app/shit/BlaBlaBlaController => BlaBlaBla
		return mb_strtolower(implode('-', preg_split('/([[:upper:]][[:lower:]]+)/', $controllerName, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY)));
	}

	/**
	 * Загружает динамически класс веб-контроллера Yii2 по его пути
	 * @param string $fileName
	 * @param string|null $moduleId
	 * @return self|null
	 * @throws InvalidConfigException
	 * @throws ReflectionException
	 * @throws Throwable
	 * @throws UnknownClassException
	 */
	public static function GetController(string $fileName, ?string $moduleId):?object {
		$className = Magic::ExtractNamespaceFromFile($fileName).'\\'.Path::ChangeFileExtension($fileName);
		if (!class_exists($className)) Yii::autoload($className);
		$class = new ReflectionClass($className);
		if ($class->isSubclassOf(__CLASS__)) {
			if (null === $moduleId) {
				$module = Yii::$app;
			} else {
				$module = PluginsSupport::GetPluginById($moduleId);
				if (null === $module) throw new InvalidConfigException("Module $moduleId not found or plugin not configured properly.");
			}
			return new $className(self::ExtractControllerId($className), $module);
		}
		return null;
	}

	/**
	 * Загружает динамически класс веб-контроллера Yii2 по его id и модулю
	 * @param string $controllerId
	 * @param string|null $moduleId
	 * @return self|null
	 */
	public static function GetControllerByControllerId(string $controllerId, ?string $moduleId):?object {
		if (null === $plugin = PluginsSupport::GetPluginById($moduleId)) throw new InvalidConfigException("Module $moduleId not found or plugin not configured properly.");
		$controllerId = implode('', array_map('ucfirst', preg_split('/-/', $controllerId, -1, PREG_SPLIT_NO_EMPTY)));
		return self::GetController("{$plugin->controllerPath}/{$controllerId}Controller.php", $moduleId);

	}

	/**
	 * Выгружает список контроллеров в указанном неймспейсе
	 * @param string $path
	 * @param string|null $moduleId
	 * @return self[]
	 * @throws InvalidConfigException
	 * @throws ReflectionException
	 * @throws Throwable
	 * @throws UnknownClassException
	 */
	public static function GetControllersList(string $path, ?string $moduleId = null):array {
		$result = [];

		$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(Yii::getAlias($path)), RecursiveIteratorIterator::SELF_FIRST);
		/** @var RecursiveDirectoryIterator $file */
		foreach ($files as $file) {
			if ($file->isFile() && 'php' === $file->getExtension() && null !== $controller = self::GetController($file->getRealPath(), $moduleId)) {
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