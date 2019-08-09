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
 * @property null|int $duration default duration in seconds before the cache will expire. If not set,
 * [[defaultDuration]] value will be used.
 * @property null|Dependency $dependency dependency of the cached item. If the dependency changes,
 * the corresponding value in the cache will be invalidated when it is fetched via [[get()]].
 * @property-read null|bool $isResultFromCache Is rendering result retrieved from cache (null if not rendered yet)
 *
 * @example Usage example:
 * ```php
 * class MyWidget extends CachedWidget {
 * // it is all, mostly
 * }
 * @todo: checkIncluded option
 */
class CachedWidget extends Widget {
	private $_isResultFromCache;
	private $_duration;
	private $_dependency;
	//todo dynamic model && resources caching options
	private $resources = [//enumerate all kind of View resources (assets, inline css/js, etc)
		'css' => [],
		'cssFiles' => [],
		'js' => [],
		'jsFiles' => [],
		'assetBundles' => []
	];

	/**
	 * {@inheritDoc}
	 */
	public function render($view, $params = []):string {
		$this->_isResultFromCache = true;
		$cacheName = self::class.$view.sha1(json_encode($params));//unique enough

		$result = Yii::$app->cache->getOrSet($cacheName, function() use ($view, $params, $cacheName) {
			$this->_isResultFromCache = false;
			$currentlyRegisteredAssets = Yii::$app->assetManager->bundles;

			$renderResult = $this->getView()->render($view, $params, $this);

			$this->resources = [
				'css' => $this->getView()->css,
				'cssFiles' => $this->getView()->cssFiles,
				'js' => $this->getView()->js,
				'jsFiles' => $this->getView()->jsFiles,
				'assetBundles' => array_diff_key(Yii::$app->assetManager->bundles, $currentlyRegisteredAssets),
			];

			Yii::$app->cache->set($cacheName."resources", $this->resources, $this->_duration, $this->_dependency);//remember all included resources
			unset($this->resources);
			return $renderResult;
		}, $this->_duration, $this->_dependency);

		if ($this->_isResultFromCache) {//rendering result retrieved from cache => register linked resources
			$this->resources = Yii::$app->cache->get($cacheName."resources");

			foreach ($this->resources['css'] as $key => $css) {
				$this->getView()->registerCss($css, [], $key);//check this
			}
			foreach ($this->resources['cssFiles'] as $key => $cssFile) {
				$this->getView()->registerCssFile($cssFile, [], $key);//check this
			}

			foreach ($this->resources['assetBundles'] as $key => $bundle) {
				$bundle::register($this->getView());
			}

			foreach ($this->resources['js'] as $position => $js) {
				foreach ($js as $hash => $jsString) {
					$this->getView()->registerJs($jsString, $position, $hash);
				}
			}

			foreach ($this->resources['jsFiles'] as $position => $jsFile) {
				$this->getView()->registerJsFile($jsFile, ['position' => $position]);
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

	/**
	 * @return null|boolean
	 */
	public function getIsResultFromCache() {
		return $this->_isResultFromCache;
	}
}