<?php
declare(strict_types = 1);

namespace app\models\core;

use app\models\references\Reference;
use ReflectionClass;
use ReflectionException;
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
	 */
	private static function ExtractControllerId($className) {
		$id =  mb_strtolower(preg_replace('/(^.+)([A-Z].+)(Controller$)/', '$2', $className));
		return "admin/{$id}/{$id}";//todo make more smarty
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