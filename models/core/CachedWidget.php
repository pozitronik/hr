<?php
declare(strict_types = 1);

namespace app\models\core;

use Yii;
use yii\base\Widget;
use yii\caching\Dependency;
use yii\web\AssetBundle;

/**
 * Class CachedWidget
 * Enable rendering caching for widgets.
 * @param null|int $duration default duration in seconds before the cache will expire. If not set,
 * [[defaultDuration]] value will be used.
 * @param null|Dependency $dependency dependency of the cached item. If the dependency changes,
 * the corresponding value in the cache will be invalidated when it is fetched via [[get()]].
 * @example Usage example:
 * ```php
 * class MyWidget extends CachedWidget {
 * // it is all, mostly
 * }
 */
class CachedWidget extends Widget {
	private $_duration;
	private $_dependency;

	/**
	 * {@inheritDoc}
	 */
	public function render($view, $params = []):string {
		$cacheName = self::class.$view.sha1(json_encode($params));//unique enough
		$unregisteredBundles = [];//Asset bundle names, that should be registered within included views/subviews

		$result = Yii::$app->cache->getOrSet($cacheName, function() use ($view, $params, $cacheName, &$unregisteredBundles) {
			$currentlyRegisteredAssets = Yii::$app->assetManager->bundles;
			$renderResult = $this->getView()->render($view, $params, $this);
			$unregisteredBundles = array_diff_key(Yii::$app->assetManager->bundles, $currentlyRegisteredAssets);
			Yii::$app->cache->set($cacheName."bundles", $unregisteredBundles, $this->_duration, $this->_dependency);//remember all included bundles
			return $renderResult;
		}, $this->_duration, $this->_dependency);
		if ([] === $unregisteredBundles) {//rendering result retrieved from cache => register linked asset bundles
			/** @var AssetBundle[] $unregisteredBundles */
			$unregisteredBundles = Yii::$app->cache->get($cacheName."bundles");
			foreach ($unregisteredBundles as $key => $bundle) {
				$bundle::register($this->getView());
			}

		}
		return $result;
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