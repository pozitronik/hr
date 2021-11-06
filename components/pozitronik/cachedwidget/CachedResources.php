<?php
declare(strict_types = 1);

namespace app\components\pozitronik\widgets;

use yii\base\Model;

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