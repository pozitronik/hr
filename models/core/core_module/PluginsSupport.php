<?php
declare(strict_types = 1);

namespace app\models\core\core_module;

use app\helpers\ArrayHelper;
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
	 * Возвращает список подключённых плагинов. Список можно задать в конфигурации, либо же вернутся все подходящие модули, подключённые в Web.php
	 * @return array Массив неймспейсов подключённых плагинов
	 * @throws InvalidConfigException
	 * @throws Throwable
	 */
	public static function ListPlugins():array {
		if (null === $plugins = ArrayHelper::getValue(Yii::$app->params, 'plugins')) {
			$plugins = [];
			foreach (Yii::$app->modules as $module) {
				if (is_object($module)) {
					$loadedModule = $module;
				} else {
					if (null === $moduleClass = ArrayHelper::getValue($module, 'class')) {
						throw new InvalidConfigException("$module entry not configured properly");
					}
					$loadedModule = new $moduleClass('temporaryId');
				}

				if ($loadedModule instanceof CoreModuleInterface) {
					$plugins[] = $module;
				}
			}
		}
		return $plugins;
	}

}