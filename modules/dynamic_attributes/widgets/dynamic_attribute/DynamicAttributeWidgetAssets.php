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
	public $sourcePath = '@app/modules/dynamic_attributes/widgets/dynamic_attribute/assets';
	public $css = [
		'css/dynamic_attribute.css'
	];
	public $js = [
		'js/dynamic_attribute.js'
	];
	public $depends = [
		AppAsset::class
	];
}