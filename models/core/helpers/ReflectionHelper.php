<?php
declare(strict_types = 1);

namespace app\models\core\helpers;

use app\helpers\Path;
use app\modules\references\models\Reference;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use Throwable;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\UnknownClassException;

/**
 * Class ReflectionHelper
 * @package app\models\core\helpers
 */
class ReflectionHelper {

	/**
	 * Вытаскивает неймспейс из файла, если он там есть
	 * @param string $path
	 * @return string|false
	 */
	public static function ExtractNamespaceFromFile(string $path) {
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
	 * Загружает и возвращает экземпляр класса при условии его существования
	 * @param string $className Имя класса
	 * @param string|null $parentClass Опциональный фильтр родительского класса
	 * @return ReflectionClass|object
	 * @throws InvalidConfigException
	 * @throws ReflectionException
	 * @throws Throwable
	 * @throws UnknownClassException
	 */
	public static function LoadClassByName(string $className, ?string $parentClass = null):object {
		if (!class_exists($className)) Yii::autoload($className);
		$class = new ReflectionClass($className);
		if ((null !== $parentClass && $class->isSubclassOf($parentClass)) || null === $parentClass) return new $className;
		throw new InvalidConfigException("Class $className not found in application scope!");
	}

	/**
	 * Загружает класс из файла (при условии одного класса в файле и совпадения имени файла с именем класса)
	 * @param string $fileName
	 * @param string|null $parentClass Опциональный фильтр родительского класса
	 * @return Reference|null|ReflectionClass
	 * @throws ReflectionException
	 * @throws Throwable
	 * @throws UnknownClassException
	 */
	public static function LoadClassFromFile(string $fileName, ?string $parentClass = null):?object {
		$className = self::ExtractNamespaceFromFile($fileName).'\\'.Path::ChangeFileExtension($fileName);
		return self::LoadClassByName($className, $parentClass);
	}

	/**
	 * Fast class name shortener
	 * app\modules\salary\models\references\RefGrades => RefGrades
	 * @param string $className
	 * @return string
	 * @throws ReflectionException
	 */
	public static function GetClassShortName(string $className):string {
		return (new ReflectionClass($className))->getShortName();
	}

	/**
	 * @param object $model
	 * @param int $filter
	 * @return array
	 * @throws ReflectionException
	 */
	public static function GetMethods(object $model, int $filter = ReflectionMethod::IS_PUBLIC):array {
		return (new ReflectionClass($model))->getMethods($filter);
	}
}