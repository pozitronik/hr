<?php
declare(strict_types = 1);

namespace app\models\core;

use Yii;
use yii\base\Widget;
use yii\caching\Dependency;

/**
 * Class CachedWidget
 * Enable rendering caching for widgets.
 * @example Usage example:
 * ```php
 * class MyWidget extends CachedWidget {
 * // iit is all, mostly
 * }
 * ```
 *
 * @param null|int $duration default duration in seconds before the cache will expire. If not set,
 * [[defaultDuration]] value will be used.
 * @param null|Dependency $dependency dependency of the cached item. If the dependency changes,
 * the corresponding value in the cache will be invalidated when it is fetched via [[get()]].
 * @package app\models\core
 *
 * @todo: кешировать и ассеты
 */
class CachedWidget extends Widget {
	private $_duration;
	private $_dependency;

	public function init() {
		parent::init();
	}

	/**
	 * {@inheritDoc}
	 */
	public function render($view, $params = []):string {
		$cacheName = self::class.$view.sha1(json_encode($params));//unique enough
		return Yii::$app->cache->getOrSet($cacheName, function() use ($view, $params) {
			return $this->getView()->render($view, $params, $this);
		}, $this->_duration, $this->_dependency);
	}

	/**
	 * @param mixed $duration
	 */
	public function setDuration(?int $duration):void {
		$this->_duration = $duration;
	}

	/**
	 * @param mixed $dependency
	 */
	public function setDependency(?Dependency $dependency):void {
		$this->_dependency = $dependency;
	}
}