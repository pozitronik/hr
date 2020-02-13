<?php
declare(strict_types = 1);

namespace app\widgets\button_controls;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class ButtonControlsWidgetAssets
 * @package app\components\button_controls
 */
class ButtonControlsWidgetAssets extends AssetBundle {
	/**
	 * @inheritdoc
	 */
	public function init() {
		$this->depends = [AppAsset::class];
		$this->sourcePath = __DIR__.'/assets';
		$this->css = ['css/button_controls.css'];
		$this->js = ['js/button_controls.js'];
		$this->publishOptions = ['forceCopy' => YII_ENV_DEV];
		parent::init();
	}
}