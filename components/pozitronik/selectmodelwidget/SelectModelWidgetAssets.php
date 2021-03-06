<?php
declare(strict_types = 1);

namespace app\components\pozitronik\selectmodelwidget;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class SelectModelWidgetAssets
 * @package app\components\select_model
 */
class SelectModelWidgetAssets extends AssetBundle {
	/**
	 * @inheritdoc
	 */
	public function init() {
		$this->depends = [AppAsset::class];
		$this->sourcePath = __DIR__.'/assets';
		$this->css = ['css/select_model.css'];
		$this->js = [
			'js/common.js',
			'js/select_model.js'
		];
		$this->publishOptions = ['forceCopy' => YII_ENV_DEV];
		parent::init();
	}
}