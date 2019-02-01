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
	public $sourcePath = '@app/modules/dynamic_attributes/widgets/attribute_field/assets';
	public $css = [
		'css/attribute_field.css'
	];
	public $js = [
		'js/attribute_field.js'
	];
	public $depends = [
		AppAsset::class
	];
}