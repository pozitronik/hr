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
	 * @param string $className
	 * @return ReflectionClass
	 * @throws ReflectionException
	 * @throws UnknownClassException
	 */
	public static function New(string $className):ReflectionClass {
		if (!class_exists($className)) Yii::autoload($className);//todo: нужен ли автолоадер? Похоже, нужен для контроллеров
		return new ReflectionClass($className);
	}

	/**
	 * Загружает и возвращает экземпляр класса при условии его существования
	 * @param string $className Имя класса
	 * @param string|null $parentClassFilter Опциональный фильтр родительского класса
	 * @return ReflectionClass|object
	 * @throws InvalidConfigException
	 * @throws ReflectionException
	 * @throws Throwable
	 * @throws UnknownClassException
	 */
	public static function LoadClassByName(string $className, ?string $parentClassFilter = null):object {
		$class = self::New($className);
		if (null === $parentClassFilter || (null !== $parentClassFilter && $class->isSubclassOf($parentClassFilter))) {
			return new $className;
		}
		throw new InvalidConfigException("Class $className not found in application scope!");
	}

	/**
	 * Загружает класс из файла (при условии одного класса в файле и совпадения имени файла с именем класса)
	 * @param string $fileName
	 * @param string|null $parentClassFilter Опциональный фильтр родительского класса
	 * @return ReflectionClass
	 * @throws ReflectionException
	 * @throws Throwable
	 * @throws UnknownClassException
	 */
	public static function LoadClassFromFile(string $fileName, ?string $parentClassFilter = null):object {
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
	 * Fast class name shortener
	 * app\modules\salary\models\references\RefGrades => RefGrades
	 * @param string $className
	 * @return string
	 * @throws ReflectionException
	 *
	 * todo: разобраться с пераметрами: строка или объект?
	 */
	public static function GetClassShortName(string $className):string {
		return $class = self::New($className)->getShortName();
	}

	/**
	 * @param object $model
	 * @param int $filter
	 * @return array
	 * @throws ReflectionException
	 *
	 * todo: разобраться с пераметрами: строка или объект?
	 */
	public static function GetMethods(object $model, int $filter = ReflectionMethod::IS_PUBLIC):array {
		return (new ReflectionClass($model))->getMethods($filter);
	}

}