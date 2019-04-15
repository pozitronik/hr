<?php
declare(strict_types = 1);

namespace app\modules\references\models;

use app\helpers\ArrayHelper;
use app\models\core\core_module\PluginsSupport;
use app\models\core\Magic;
use ReflectionException;
use Throwable;
use Yii;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\base\UnknownClassException;

/**
 * Class ReferenceLoader
 * @package app\modules\references
 *
 * @property Reference[] $list [$referenceClassName] => object Reference
 *
 */
class ReferenceLoader extends Model {
	public const REFERENCES_DIRECTORY = '@app/models/references';

	/**
	 * @return Reference[]
	 * @throws ReflectionException
	 * @throws Throwable
	 * @throws InvalidConfigException
	 * @throws UnknownClassException
	 */
	public static function getList():array {
		$baseReferences = [];
		$baseReferencesDir = Yii::getAlias(self::REFERENCES_DIRECTORY);

		if (file_exists($baseReferencesDir)) {//Загрузить базовые модели референсов
			$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($baseReferencesDir), RecursiveIteratorIterator::SELF_FIRST);
			/** @var RecursiveDirectoryIterator $file */
			foreach ($files as $file) {
				if ($file->isFile() && 'php' === $file->getExtension() && null !== $model = Magic::LoadClassFromFilename($file->getRealPath(), Reference::class)) {
					$baseReferences[$model->formName()] = $model;
				}
			}
		}
		$pluginsReferences = PluginsSupport::GetAllReferences();//загрузить модульные модели референсов
		return array_merge($baseReferences, $pluginsReferences);
	}

	/**
	 * @param string $className
	 * @return Reference|null
	 * @throws Throwable
	 */
	public static function getReferenceByClassName(string $className):?Reference {
		return ArrayHelper::getValue(self::getList(), $className);
	}
}