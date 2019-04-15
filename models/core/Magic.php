<?php
declare(strict_types = 1);

namespace app\models\core;

use app\helpers\ArrayHelper;
use app\modules\references\models\Reference;
use app\modules\privileges\models\UserRightInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use Throwable;
use app\helpers\Path;
use Yii;
use yii\base\InvalidConfigException;
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
	 * Возвращает все экшены контроллера
	 * @param Controller $controllerClass
	 * @return string[]
	 * @throws ReflectionException
	 */
	public static function GetControllerActions(Controller $controllerClass):array {
		$class = new ReflectionClass($controllerClass);
		$publicMethods = $class->getMethods(ReflectionMethod::IS_PUBLIC);
		$names = ArrayHelper::getColumn($publicMethods, 'name');
		return preg_filter('/^action([A-Z])(\w+?)/', '$1$2', $names);
	}

	/**
	 * Переводит вид имени экшена к виду запроса, который этот экшен дёргает.
	 * @param string $action
	 * @return string
	 * @example actionSomeActionName => some-action-name
	 * @example OtherActionName => other-action-name
	 */
	public static function GetActionRequestName(string $action):string {
		$lines = preg_split('/(?=[A-Z])/', $action, -1, PREG_SPLIT_NO_EMPTY);
		if ('action' === $lines[0]) unset($lines[0]);
		return mb_strtolower(implode('-', $lines));
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
	public static function LoadClassFromFilename(string $fileName, ?string $parentClass = null):?object {
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
	 * @param string $property
	 * @return bool
	 * @throws ReflectionException
	 */
	public static function HasProperty(object $model, string $property):bool {
		return (new ReflectionClass($model))->hasProperty($property);
	}

}