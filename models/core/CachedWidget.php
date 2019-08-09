<?php
declare(strict_types = 1);

namespace app\models\core;

use Yii;
use yii\base\Model;
use yii\base\Widget;
use yii\caching\Dependency;

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
	//todo resources caching options
	/** @var CachedResources|null $resources */
	private $resources;

	public function init() {
		parent::init();
		$this->resources = new CachedResources();
	}

	/**
	 * {@inheritDoc}
	 */
	public function render($view, $params = []):string {
		$cacheName = self::class.$view.sha1(json_encode($params));//unique enough
		if (true === $this->_isResultFromCache = Yii::$app->cache->exists($cacheName)) {//rendering result retrieved from cache => register linked resources
			$this->resources->attributes = Yii::$app->cache->get($cacheName."resources");

			foreach ($this->resources->metaTags as $key => $metaTag) {
				if (is_numeric($key)) {
					$this->getView()->metaTags[] = $metaTag;//tags registered as string, not as convertible array
				} else {
					$this->getView()->metaTags[$key] = $metaTag;
				}
			}

			foreach ($this->resources->linkTags as $key => $linkTag) {
				if (is_numeric($key)) {
					$this->getView()->linkTags[] = $linkTag;//tags registered as string, not as convertible array
				} else {
					$this->getView()->linkTags[$key] = $linkTag;
				}
			}

			foreach ($this->resources->css as $key => $css) {
				if (is_numeric($key)) {
					$this->getView()->css[] = $css;//inline css registered as string, not as convertible array
				} else {
					$this->getView()->css[$key] = $css;
				}

			}
			foreach ($this->resources->cssFiles as $key => $cssFile) {
				/**
				 * $cssFile is already prepared html-string
				 * If resource has registered with asset dependency, then it placed in assetBundles section, see \yii\web\View::registerCssFile
				 */
				$this->getView()->registerCssFile($cssFile, [], $key);
			}

			foreach ($this->resources->js as $position => $js) {
				foreach ($js as $hash => $jsString) {
					$this->getView()->registerJs($jsString, $position, $hash);
				}
			}

			foreach ($this->resources->jsFiles as $position => $jsFile) {
				$this->getView()->registerJsFile($jsFile, ['position' => $position]);
			}

			foreach ($this->resources->assetBundles as $key => $bundle) {
				$this->getView()->assetBundles[] = $bundle;
			}
		}

		return Yii::$app->cache->getOrSet($cacheName, function() use ($view, $params, $cacheName) {
			$this->_isResultFromCache = false;
			$currentlyRegisteredAssets = Yii::$app->assetManager->bundles;

			$renderResult = $this->getView()->render($view, $params, $this);

			Yii::$app->cache->set($cacheName."resources", [
				'metaTags' => $this->getView()->metaTags,
				'linkTags' => $this->getView()->linkTags,
				'css' => $this->getView()->css,
				'cssFiles' => $this->getView()->cssFiles,
				'js' => $this->getView()->js,
				'jsFiles' => $this->getView()->jsFiles,
				'assetBundles' => array_diff_key(Yii::$app->assetManager->bundles, $currentlyRegisteredAssets)
			], $this->_duration, $this->_dependency);//remember all included resources
			return $renderResult;
		}, $this->_duration, $this->_dependency);
	}

	/**
	 * @param null|int $duration
	 */
	public function setDuration(?int $duration):void {
		$this->_duration = $duration;
	}

	/**
	 * @param null|Dependency $dependency
	 */
	public function setDependency(?Dependency $dependency):void {
		$this->_dependency = $dependency;
	}

	/**
	 * @return null|bool
	 */
	public function getIsResultFromCache():?bool {
		return $this->_isResultFromCache;
	}
}

/**
 * Class CachedResources
 * Simply describe all kinds of linked resources (js, css, etc)
 *
 * @property array $assetBundles
 * @property array $css
 * @property array $jsFiles
 * @property array $metaTags
 * @property array[] $js
 * @property array $linkTags
 * @property array $cssFiles
 */
class CachedResources extends Model {
	private $_metaTags = [];
	private $_linkTags = [];
	private $_css = [];
	private $_cssFiles = [];
	private $_js = [];
	private $_jsFiles = [];
	private $_assetBundles = [];

	/**
	 * {@inheritDoc}
	 */
	public function rules():array {
		return [
			[['assetBundles', 'css', 'jsFiles', 'metaTags', 'js', 'linkTags', 'cssFiles'], 'safe']
		];
	}

	/**
	 * @return array
	 */
	public function getMetaTags():array {
		return $this->_metaTags;
	}

	/**
	 * @param array $metaTags
	 */
	public function setMetaTags($metaTags):void {
		$this->_metaTags = $metaTags;
	}

	/**
	 * @return array
	 */
	public function getLinkTags():array {
		return $this->_linkTags;
	}

	/**
	 * @param array $linkTags
	 */
	public function setLinkTags($linkTags):void {
		$this->_linkTags = $linkTags;
	}

	/**
	 * @return array
	 */
	public function getCss():array {
		return $this->_css;
	}

	/**
	 * @param array $css
	 */
	public function setCss($css):void {
		$this->_css = $css;
	}

	/**
	 * @return array
	 */
	public function getCssFiles():array {
		return $this->_cssFiles;
	}

	/**
	 * @param array $cssFiles
	 */
	public function setCssFiles($cssFiles):void {
		$this->_cssFiles = $cssFiles;
	}

	/**
	 * @return array
	 */
	public function getJs():array {
		return $this->_js;
	}

	/**
	 * @param array $js
	 */
	public function setJs($js):void {
		$this->_js = $js;
	}

	/**
	 * @return array
	 */
	public function getJsFiles():array {
		return $this->_jsFiles;
	}

	/**
	 * @param array $jsFiles
	 */
	public function setJsFiles($jsFiles):void {
		$this->_jsFiles = $jsFiles;
	}

	/**
	 * @return array
	 */
	public function getAssetBundles():array {
		return $this->_assetBundles;
	}

	/**
	 * @param array $assetBundles
	 */
	public function setAssetBundles($assetBundles):void {
		$this->_assetBundles = $assetBundles;
	}
}