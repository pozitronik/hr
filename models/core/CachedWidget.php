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
 * @todo: checkIncluded
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
		$unregisteredJS = [];
		$unregisteredJSFiles = [];
		$result = Yii::$app->cache->getOrSet($cacheName, function() use ($view, $params, $cacheName, &$unregisteredBundles, &$unregisteredJS, &$unregisteredJSFiles) {
			$currentlyRegisteredAssets = Yii::$app->assetManager->bundles;
			$currentlyRegisteredJS = $this->getView()->js;
			$currentlyRegisteredJSFiles = $this->getView()->jsFiles;

			$renderResult = $this->getView()->render($view, $params, $this);

			$unregisteredBundles = array_diff_key(Yii::$app->assetManager->bundles, $currentlyRegisteredAssets);
			$unregisteredJS = array_diff_key($this->getView()->js, $currentlyRegisteredJS);
			$unregisteredJSFiles = array_diff_key($this->getView()->jsFiles, $currentlyRegisteredJSFiles);

			Yii::$app->cache->set($cacheName."bundles", $unregisteredBundles, $this->_duration, $this->_dependency);//remember all included bundles
			Yii::$app->cache->set($cacheName."js", $unregisteredJS, $this->_duration, $this->_dependency);//remember all included js
			Yii::$app->cache->set($cacheName."jsfiles", $unregisteredJSFiles, $this->_duration, $this->_dependency);//remember all included js files
			return $renderResult;
		}, $this->_duration, $this->_dependency);
		if ([] === $unregisteredBundles) {//rendering result retrieved from cache => register linked asset bundles
			/** @var AssetBundle[] $unregisteredBundles */
			$unregisteredBundles = Yii::$app->cache->get($cacheName."bundles");
			foreach ($unregisteredBundles as $key => $bundle) {
				$bundle::register($this->getView());
			}

		}

		if ([] === $unregisteredJS) {
			$unregisteredJS = Yii::$app->cache->get($cacheName."js");
			foreach ($unregisteredJS as $position => $js) {
				foreach ($js as $hash => $jsString) {
					$this->getView()->registerJs($jsString, $position);
				}

			}

		}

		if ([] === $unregisteredJSFiles) {
			$unregisteredJSFiles = Yii::$app->cache->get($cacheName."jsfiles");
			foreach ($unregisteredJSFiles as $position => $js) {//todo position
				$this->getView()->registerJsFile($js, ['position' => $position]);
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