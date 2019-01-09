<?php
declare(strict_types = 1);

namespace app\models\core;

use app\helpers\ArrayHelper;
use app\models\references\Reference;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use Yii;
use app\helpers\Path;
use yii\base\UnknownClassException;
use yii\web\Controller;

/**
 * Class Magic
 * Хелпер с волшебством и магией. Надязыковой анализ и загрузка классов для реализации некоторых внеземных фич
 * @package app\models\core
 */
class Magic {

	/**
	 * Вытаскивает неймспейс из файла, если он там есть
	 * @param string $path
	 * @return string|false
	 */
	public static function ExtractNamespaceFromFile($path) {
		$lines = file($path);
		foreach ($lines as $line) {
			$line = trim($line);
			if (preg_match('/^namespace\W(.*);$/', $line)) {
				return preg_replace('/(^namespace\W)(.*)(;$)/', '$2', $line);
			}
		}
		return false;
	}

	/**
	 * @param string $className
	 * @return false|string
	 * @todo: функция не умеет преобразовывать имена классов по той же схеме, что Yii. Реализован простейший вариант, а вот что-то вроде MassUpdateController к mass-update эта регулярка уже не вернёт.
	 */
	private static function ExtractControllerId($className) {
		$id = mb_strtolower(preg_replace('/(^.+)([A-Z].+)(Controller$)/', '$2', $className));
		return "admin/{$id}";
	}

	/**
	 * Загружает динамически класс веб-контроллера Yii2 по его пути
	 * @param string $fileName
	 * @return Controller|false
	 * @throws ReflectionException
	 * @throws UnknownClassException
	 */
	public static function GetController($fileName) {
		$className = self::ExtractNamespaceFromFile($fileName).'\\'.Path::ChangeFileExtension($fileName);

		if (!class_exists($className)) Yii::autoload($className);
		$class = new ReflectionClass($className);
		if ($class->isSubclassOf(Controller::class)) {
			return new $className(self::ExtractControllerId($className), Yii::$app);
		}
		return false;
	}

	/**
	 * Возвращает все экшены контроллера
	 * @param Controller $controllerClass
	 * @return string[]
	 * @throws ReflectionException
	 */
	public static function GetControllerActions(object $controllerClass):array {
		$class = new ReflectionClass($controllerClass);
		$publicMethods = $class->getMethods(ReflectionMethod::IS_PUBLIC);
		$names = ArrayHelper::getColumn($publicMethods, 'name');
		return preg_filter('/^action([A-Z])(\w+?)/', '$1$2', $names);
	}

	/**
	 * Загружает динамически класс справочника Yii2 по его пути
	 * @param string $fileName
	 * @return Reference|false
	 * @throws ReflectionException
	 * @throws UnknownClassException
	 */
	public static function GetReferenceModel($fileName) {
		$className = self::ExtractNamespaceFromFile($fileName).'\\'.Path::ChangeFileExtension($fileName);

		if (!class_exists($className)) Yii::autoload($className);
		$class = new ReflectionClass($className);
		if ($class->isSubclassOf(Reference::class)) {
			return new $className(/*['className'=>$className]*/);
		}
		return false;
	}

	/**
	 * @param $controller
	 * @param $property
	 * @return bool
	 * @throws ReflectionException
	 */
	public static function hasProperty($controller, $property):bool {
		return (new ReflectionClass($controller))->hasProperty($property);
	}

}