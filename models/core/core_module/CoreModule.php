<?php
declare(strict_types = 1);

namespace app\models\core\core_module;

use app\helpers\ArrayHelper;
use Throwable;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Module as BaseModule;

/**
 * Class CoreModule
 * @package app\models\core\core_module
 */
class CoreModule extends BaseModule implements CoreModuleInterface {

	/**
	 * {@inheritDoc}
	 */
	public function __construct(string $id, $parent = null, array $config = []) {
		parent::__construct($id, $parent, $config);
		$this->controllerNamespace = "app\modules\\{$this->id}\\controllers";
		$this->defaultRoute = $this->id;
	}

	/**
	 * @param string $id
	 * @return CoreModule
	 * @throws InvalidConfigException
	 * @throws Throwable
	 */
	public static function getModuleById(string $id):self {
		if (null === $module = ArrayHelper::getValue(Yii::$app->modules, $id)) {
			throw new InvalidConfigException("Модуль $id не подключён");
		}
		return $module;
	}

}