<?php
declare(strict_types = 1);

namespace app\widgets\navbar;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class NavbarWidgetAssets
 * @package app\components\navbar
 */
class NavbarWidgetAssets extends AssetBundle {
	/**
	 * @inheritdoc
	 */
	public function init() {
		$this->depends = [AppAsset::class];
		$this->sourcePath = __DIR__.'/assets';
		$this->css = ['css/navbar.css'];
		$this->js = ['js/navbar.js'];
		$this->publishOptions = ['forceCopy' => YII_ENV_DEV];
		parent::init();
	}
}








