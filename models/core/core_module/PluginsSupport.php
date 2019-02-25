<?php
declare(strict_types = 1);

namespace app\models\core\core_module;

use app\helpers\ArrayHelper;
use app\models\core\Magic;
use function array_key_first;
use Throwable;
use Yii;
use yii\base\InvalidConfigException;

/**
 * Class PluginsSupport
 * @package app\models\core\core_module
 *
 * Ядро поддержки расширений. Обходит все подключённые модули, выбирая из них те, что имеют интерйефс CoreModuleInterface.
 * Найденные модули трактются, как плагины: меж ними проверяются зависимости, выбираются данные/регистрируются функции.
 * Как обычно, прототипируем по мере написания.
 */
class PluginsSupport {

	/**
	 * @param string $name - id плагина из web.php
	 * @param array $pluginConfigurationArray - конфиг плагина из web.php вида
	 * [
	 *        'class' => Module::class,
	 *        ...
	 * ]
	 * @return null|CoreModule - загруженный экземпляр модуля
	 * @throws InvalidConfigException
	 * @throws Throwable
	 */
	private static function LoadPlugin(string $name, array $pluginConfigurationArray):?CoreModule {
		if (null === $moduleClass = ArrayHelper::getValue($pluginConfigurationArray, 'class')) {
			throw new InvalidConfigException("Module not configured properly");
		}
		$module = new $moduleClass($name);
		if ($module instanceof CoreModule) return $module;
		return null;
	}

	/**
	 * Возвращает список подключённых плагинов. Список можно задать в конфигурации, либо же вернутся все подходящие модули, подключённые в Web.php
	 * @return CoreModule[] Массив подключённых плагинов
	 * @throws InvalidConfigException
	 * @throws Throwable
	 */
	public static function ListPlugins():array {
		if (null === $plugins = ArrayHelper::getValue(Yii::$app->params, 'plugins')) {
			$plugins = [];
			foreach (Yii::$app->modules as $name => $module) {
				if (is_object($module)) {
					if ($module instanceof CoreModule) $plugins[] = $module;
				} else {

					if (null !== $loadedModule = self::LoadPlugin($name, $module)) {
						$plugins[] = $loadedModule;
					}
				}
			}
		}
		return $plugins;
	}

	/**
	 * Возращает массив путей к контроллерам плагинов, дальше WigetableController по ним построит навигацию
	 * @return string[]
	 * @throws InvalidConfigException
	 * @throws Throwable
	 */
	public static function ListPluginsControllersDirs():array {
		$result = [];
		foreach (self::ListPlugins() as $plugin) {
			$result[] = $plugin->controllerPath;
		}
		return $result;
	}
}