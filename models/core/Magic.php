<?php
declare(strict_types = 1);

namespace app\models\core;

use app\helpers\ArrayHelper;
use app\models\relations\RelUsersGroups;
use app\modules\groups\models\Groups;
use app\modules\references\models\Reference;
use app\modules\privileges\models\UserRightInterface;
use app\modules\users\models\Users;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use Throwable;
use Yii;
use app\helpers\Path;
use yii\base\Configurable;
use yii\base\UnknownClassException;
use yii\web\Controller;

/**
 * Class Magic
 * Хелпер с волшебством и магией. Надязыковой анализ и загрузка классов для реализации некоторых внеземных фич
 * @package app\models\core
 */
class Magic {
	/*очень временное и очень хуёвое решение на время протипирования*/
	public static $classMap = [
		'Users' => Users::class,
		'Groups' => Groups::class,
		'RelUsersGroups' => RelUsersGroups::class
	];

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
	 * Загружает динамически класс справочника Yii2 по его пути
	 * @param string $fileName
	 * @return Reference|null|ReflectionClass
	 * @throws ReflectionException
	 * @throws Throwable
	 * @throws UnknownClassException
	 */
	public static function GetReferenceModel(string $fileName):?object {
		$className = self::ExtractNamespaceFromFile($fileName).'\\'.Path::ChangeFileExtension($fileName);
		return self::LoadClassByName($className, Reference::class);

	}

	/**
	 * Загружает динамически класс права пользователя по его пути
	 * @param string $fileName
	 * @return UserRightInterface|null|ReflectionClass
	 * @throws ReflectionException
	 * @throws Throwable
	 * @throws UnknownClassException
	 */
	public static function GetUserRightModel(string $fileName):?object {
		$className = self::ExtractNamespaceFromFile($fileName).'\\'.Path::ChangeFileExtension($fileName);
		return self::LoadClassByName($className, UserRightInterface::class);
	}

	/**
	 * Загружает и возвращает экземпляр класса при условии его существования
	 * @param string $className Имя класса
	 * @param string|null $parentClass Опциональный фильтр родительского класса
	 * @return ReflectionClass|object|null
	 * @throws ReflectionException
	 * @throws UnknownClassException
	 * @throws Throwable
	 */
	public static function LoadClassByName(string $className, ?string $parentClass = null):?object {
		$className = ArrayHelper::getValue(self::$classMap, $className, $className);//если имя есть в карте, подставляем значение оттуда
		if (!class_exists($className)) Yii::autoload($className);
		$class = new ReflectionClass($className);
		if ((null !== $parentClass && $class->isSubclassOf($parentClass)) || null === $parentClass) return new $className;

		return null;
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
	 * @param Configurable $controller
	 * @param string $property
	 * @return bool
	 * @throws ReflectionException
	 */
	public static function hasProperty(Configurable $controller, string $property):bool {
		return (new ReflectionClass($controller))->hasProperty($property);
	}

}