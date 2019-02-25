<?php
declare(strict_types = 1);

namespace app\modules\references\models;

use app\helpers\ArrayHelper;
use app\models\core\core_module\PluginsSupport;
use app\models\core\Magic;
use Yii;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use yii\base\Model;

/**
 * Class ReferenceLoader
 * @package app\modules\references
 *
 * @property Reference[] $list [$referenceClassName] => object Reference
 *
 */
class ReferenceLoader extends Model {
	public const REFERENCES_DIRECTORY = '@app/modules/references/models/refs';
	/**
	 * @return Reference[]
	 */
	public static function getList():array {
		$baseReferences = [];

		$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(Yii::getAlias(self::REFERENCES_DIRECTORY)), RecursiveIteratorIterator::SELF_FIRST);
		/** @var RecursiveDirectoryIterator $file */
		foreach ($files as $file) {
			if ($file->isFile() && 'php' === $file->getExtension() && null !== $model = Magic::GetReferenceModel($file->getRealPath())) {
				$baseReferences[$model->formName()] = $model;
			}
		}
		$pluginsReferences = PluginsSupport::GetAllReferences();
		return array_merge($baseReferences, $pluginsReferences);
	}

	/**
	 * @param string $className
	 * @return Reference|null
	 */
	public static function getReferenceByClassName(string $className):?Reference {
		return ArrayHelper::getValue(self::getList(), $className);
	}
}