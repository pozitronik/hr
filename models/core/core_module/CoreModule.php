<?php
declare(strict_types = 1);

namespace app\models\core\core_module;

use app\helpers\ArrayHelper;
use app\models\core\Magic;
use app\modules\privileges\models\UserRightInterface;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Throwable;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Module as BaseModule;

/**
 * Class CoreModule
 * @package app\models\core\core_module
 *
 * @property-read string $name
 * @property-read string $namespace
 * @property-read string $alias
 */
class CoreModule extends BaseModule implements CoreModuleInterface {
	protected $_namespace;
	protected $_alias;

	/**
	 * {@inheritDoc}
	 */
	public function __construct(string $id, $parent = null, array $config = []) {
		parent::__construct($id, $parent, $config);
		$this->defaultRoute = $this->id;
	}

	/**
	 * @param string $id
	 * @return CoreModule
	 * @throws InvalidConfigException
	 * @throws Throwable
	 * @unused
	 * @deprecated
	 */
	public static function getModuleById(string $id):self {
		if (null === $module = ArrayHelper::getValue(Yii::$app->modules, $id)) {
			throw new InvalidConfigException("Модуль $id не подключён");
		}
		return $module;
	}

	/**
	 * Функиция генерирует пункт меню навигации внутри модуля
	 * @param string $label
	 * @param string|null $route
	 * @param array $parameters
	 * @return array
	 */
	public static function breadcrumbItem(string $label, ?string $route = null, array $parameters = []):array {
		$module = Yii::$app->controller->module;
		$route = $route?:$module->defaultRoute;
		return ['label' => $label, 'url' => ["/{$module->id}/{$route}"] + $parameters];
	}

	/**
	 * Возвращает название плагина
	 * @return string
	 */
	public function getName():string {
		return $this->id;
	}

	/**
	 * Возвращает неймспейс загруженного модуля (для вычисления алиасных путей внутри модуля)
	 * @return string
	 */
	public function getNamespace():string {
		if (null === $this->_namespace) {
			$class = get_class($this);
			if (false !== ($pos = strrpos($class, '\\'))) {
				$this->_namespace = substr($class, 0, $pos);
			}
		}
		return $this->_namespace;
	}

	/**
	 * Возвращает зарегистрированный алиас модуля
	 * @return string
	 */
	public function getAlias():string {
		if (null === $this->_alias) {
			/*Регистрируем алиас плагина*/
			$this->_alias = "@{$this->id}";
			Yii::setAlias($this->_alias, $this->basePath);
		}

		return $this->_alias;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getRightsList(array $excludedRights = []):array {
		$result = [];
		$rightsDir = Yii::getAlias($this->alias."/models/rights/");
		if (file_exists($rightsDir)) {

			$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($rightsDir), RecursiveIteratorIterator::SELF_FIRST);
			$excludedIds = ArrayHelper::getColumn($excludedRights, 'id');
			/** @var RecursiveDirectoryIterator $file */
			foreach ($files as $file) {
				if (($file->isFile() && 'php' === $file->getExtension() && null !== $model = Magic::LoadClassFromFilename($file->getRealPath(), UserRightInterface::class)) && (!$model->hidden) && (!in_array($model->id, $excludedIds))) {
					$model->module = $this->name;
					$result[] = $model;
				}
			}
		}
		return $result;
	}

}