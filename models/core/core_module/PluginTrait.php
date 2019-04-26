<?php
declare(strict_types = 1);

namespace app\models\core\core_module;

use Throwable;
use Yii;
use yii\base\InvalidConfigException;

/**
 * Дополнительный функционал, добавляемый к моделям, находящимся внутри модулей
 * Trait PluginTrait
 * @package app\models\core\core_module
 *
 * @property-read CoreModule|null $plugin
 */
trait PluginTrait {

	/**
	 * Вычисляет плагин, внутри которого находится модель
	 * @return CoreModule|null
	 * @throws Throwable
	 * @throws InvalidConfigException
	 */
	public function getPlugin():?CoreModule {
		foreach (PluginsSupport::ListPlugins() as $plugin) {
			$currentPluginNamespace = $plugin->namespace;
			if (0 === strncmp(static::class, $currentPluginNamespace, strlen($currentPluginNamespace))) {
				return $plugin;
			}
		}
		return null;
	}

	/**
	 * @param string $text
	 * @param null $url
	 * @param array $options
	 * @return string
	 * @throws InvalidConfigException
	 * @throws Throwable
	 */
	public function a(string $text, $url = null, array $options = []):string {
		return $this->plugin::a($text, $url, $options);
	}

}