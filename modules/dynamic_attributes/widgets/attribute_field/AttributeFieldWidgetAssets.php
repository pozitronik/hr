<?php
declare(strict_types = 1);

namespace app\modules\dynamic_attributes\widgets\attribute_field;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class AttributeFieldWidgetAssets
 * @package app\modules\dynamic_attributes\widgets\attribute_field
 */
class AttributeFieldWidgetAssets extends AssetBundle {
	/**
	 * @inheritdoc
	 */
	public function init() {
		$this->depends = [AppAsset::class];
		$this->sourcePath = __DIR__.'/assets';
		$this->css = ['css/attribute_field.css'];
		$this->js = ['js/attribute_field.js'];
		$this->publishOptions = ['forceCopy' => YII_ENV_DEV];
		parent::init();
	}
}