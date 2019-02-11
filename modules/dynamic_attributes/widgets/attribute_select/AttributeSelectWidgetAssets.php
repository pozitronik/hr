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
	public $sourcePath = '@app/modules/dynamic_attributes/widgets/attribute_select/assets';
	public $css = [
		'css/attribute_select.css'
	];
	public $js = [
		'js/attribute_select.js'
	];
	public $depends = [
		AppAsset::class
	];
}