<?php
declare(strict_types = 1);

namespace app\modules\dynamic_attributes\widgets\dynamic_attribute;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class DynamicAttributeWidgetAssets
 * @package app\modules\dynamic_attributes\widgets\dynamic_attribute
 */
class DynamicAttributeWidgetAssets extends AssetBundle {
	/**
	 * @inheritdoc
	 */
	public function init() {
		$this->depends = [AppAsset::class];
		$this->sourcePath = __DIR__.'/assets';
		$this->css = ['css/dynamic_attribute.css'];
		$this->js = ['js/dynamic_attribute.js'];
		$this->publishOptions = ['forceCopy' => YII_ENV_DEV];
		parent::init();
	}
}