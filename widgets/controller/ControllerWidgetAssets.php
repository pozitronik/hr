<?php
declare(strict_types = 1);

namespace app\widgets\controller;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class ControllerWidgetAssets
 * @package app\components\controller
 */
class ControllerWidgetAssets extends AssetBundle {
	/**
	 * @inheritdoc
	 */
	public function init() {
		$this->depends = [AppAsset::class];
		$this->sourcePath = __DIR__.'/assets';
		$this->css = ['css/controller.css'];
		$this->js = ['js/controller.js'];
		$this->publishOptions = ['forceCopy' => YII_ENV_DEV];
		parent::init();
	}
}








