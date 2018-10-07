<?php

namespace app\models\core;

use ReflectionClass;
use Yii;
use app\helpers\Path;
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
//todo
		//		Yii::$app->controllerPath;
		return preg_replace('/(^.+)([A-Z].+)(Controller$)/', '$2', $className);
//		return 'users/users';
	}

	/**
	 * Загружает динамически класс веб-контроллера Yii2 по его пути
	 * @param string $fileName
	 * @return Controller|false
	 */
	public static function GetController($fileName) {
		$className = self::ExtractNamespaceFromFile($fileName).'\\'.Path::ChangeFileExtension($fileName);

		Yii::autoload($className);
		$class = (new ReflectionClass($className));
		if ($class->isSubclassOf('yii\web\Controller')) {
			return new $className(self::ExtractControllerId($className), Yii::$app);
		}
		return false;
	}
}