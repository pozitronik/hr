<?php
declare(strict_types = 1);

namespace app\widgets\ribbon;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class RibbonWidgetAssets
 * @package app\components\ribbon
 */
class RibbonWidgetAssets extends AssetBundle {
	/**
	 * @inheritdoc
	 */
	public function init() {
		$this->depends = [AppAsset::class];
		$this->sourcePath = __DIR__.'/assets';
		$this->css = ['css/ribbon.css'];
		$this->js = ['js/ribbon.js'];
		$this->publishOptions = ['forceCopy' => YII_ENV_DEV];
		parent::init();
	}
}