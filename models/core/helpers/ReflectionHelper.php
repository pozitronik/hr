<?php
declare(strict_types = 1);

namespace app\models\core\helpers;

use app\helpers\Path;
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
	 * Инициализирует рефлектор, но не загружает класс
	 * @param string|object $className Имя класса/экземпляр класса
	 * @return ReflectionClass|null
	 * @throws ReflectionException
	 * @throws UnknownClassException
	 */
	public static function New($className, $throwOnFail = true):?ReflectionClass {
		if (is_string($className) && !class_exists($className)) Yii::autoload($className);
		try {
			return new ReflectionClass($className);
		} catch (Throwable $t) {
			if ($throwOnFail) throw $t;
		}
		return null;
	}

	/**
	 * Загружает и возвращает экземпляр класса при условии его существования
	 * @param string $className Имя класса
	 * @param null|string[] $parentClassFilter Опциональный фильтр родительского класса
	 * @param bool $throwOnFail true - упасть при ошибке, false - вернуть null
	 * @return ReflectionClass|object|null
	 * @throws InvalidConfigException
	 * @throws ReflectionException
	 * @throws UnknownClassException
	 */
	public static function LoadClassByName(string $className, ?array $parentClassFilter = null, $throwOnFail = true):?object {
		if (null === $class = self::New($className, $throwOnFail)) return null;
		if (self::IsInSubclassOf($class, $parentClassFilter)) return new $className;
		if ($throwOnFail) throw new InvalidConfigException("Class $className not found in application scope!");
		return null;
	}

	/**
	 * Загружает класс из файла (при условии одного класса в файле и совпадения имени файла с именем класса)
	 * @param string $fileName
	 * @param string[]|null $parentClassFilter Опциональный фильтр родительского класса
	 * @return ReflectionClass
	 * @throws ReflectionException
	 * @throws Throwable
	 * @throws UnknownClassException
	 */
	public static function LoadClassFromFile(string $fileName, ?array $parentClassFilter = null):object {
		return self::LoadClassByName(self::GetClassNameFromFile($fileName), $parentClassFilter);
	}

	/**
	 * Возвращает имя класса, находящегося в файле (при условии одного класса в файле и совпадения имени файла с именем класса)
	 * @param string $fileName
	 * @return string
	 */
	public static function GetClassNameFromFile(string $fileName):string {
		return self::ExtractNamespaceFromFile($fileName).'\\'.Path::ChangeFileExtension($fileName);
	}

	/**
	 * Проверяет, является ли класс потомков одного из перечисленных классов
	 * @param ReflectionClass $class проверяемый класс
	 * @param null|string[] $subclassesList список родительских классов для проверки (null - не проверять)
	 * @return bool
	 */
	public static function IsInSubclassOf(ReflectionClass $class, ?array $subclassesList = null):bool {
		if (null === $subclassesList) return true;
		foreach ($subclassesList as $subclass) {
			if ($class->isSubclassOf($subclass)) return true;
		}
		return false;
	}

	/**
	 * Fast class name shortener
	 * app\modules\salary\models\references\RefGrades => RefGrades
	 * @param string $className
	 * @return string
	 * @throws ReflectionException
	 * @throws UnknownClassException
	 */
	public static function GetClassShortName(string $className):string {
		return self::New($className)->getShortName();
	}

	/**
	 * @param object $model
	 * @param int $filter
	 * @return array
	 * @throws ReflectionException
	 * @throws UnknownClassException
	 */
	public static function GetMethods(object $model, int $filter = ReflectionMethod::IS_PUBLIC):array {
		return self::New($model)->getMethods($filter);
	}

}