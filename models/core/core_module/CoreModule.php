<?php
declare(strict_types = 1);

namespace app\models\core\core_module;

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
	 * Функция должна вернуть корневой путь модуля (ровно тот же, что указан в web.php)
	 * @return string
	 * @throws InvalidConfigException
	 */
	public static function Root():string {
		throw new InvalidConfigException('Модуль не имеет определения корневого пути!');
	}

	/**
	 * @inheritdoc
	 */
	public function getRoute(?string $controller = null, ?string $action = '', array $parameters = []):array {
		$controller = $controller??$this->defaultRoute;
		return array_merge(["{$this->id}/{$controller}/{$action}"], $parameters);
	}

}