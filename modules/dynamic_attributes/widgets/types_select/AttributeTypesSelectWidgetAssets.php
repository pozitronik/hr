<?php
declare(strict_types = 1);

namespace app\modules\dynamic_attributes\widgets\types_select;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class AttributeTypesSelectWidgetAssets
 * @package app\components\attribute_types_select
 */
class AttributeTypesSelectWidgetAssets extends AssetBundle {
	/**
	 * @inheritdoc
	 */
	public function init() {
		$this->depends = [AppAsset::class];
		$this->sourcePath = __DIR__.'/assets';
		$this->css = ['css/attribute_types_select.css'];
		$this->js = ['js/attribute_types_select.js'];
		$this->publishOptions = ['forceCopy' => YII_ENV_DEV];
		parent::init();
	}
}