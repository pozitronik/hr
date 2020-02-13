<?php
declare(strict_types = 1);

namespace app\modules\dynamic_attributes\widgets\attribute_select;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class AttributeSelectWidgetAssets
 * @package app\components\attribute_select
 */
class AttributeSelectWidgetAssets extends AssetBundle {

	/**
	 * @inheritdoc
	 */
	public function init() {
		$this->depends = [AppAsset::class];
		$this->sourcePath = __DIR__.'/assets';
		$this->css = ['css/attribute_select.css'];
		$this->js = ['js/attribute_select.js'];
		$this->publishOptions = ['forceCopy' => YII_ENV_DEV];
		parent::init();
	}

}