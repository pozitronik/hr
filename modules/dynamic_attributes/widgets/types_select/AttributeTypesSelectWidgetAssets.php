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
	public $sourcePath = '@app/modules/dynamic_attributes/widgets/types_select/assets';
	public $css = [
		'css/attribute_types_select.css'
	];
	public $js = [
		'js/attribute_types_select.js'
	];
	public $depends = [
		AppAsset::class
	];
}