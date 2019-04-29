<?php
declare(strict_types = 1);

namespace app\modules\dynamic_attributes\widgets\user_attribute;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class UserAttributesWidgetAssets
 * @package app\widgets\attribute
 */
class UserAttributeWidgetAssets extends AssetBundle {
	public $sourcePath = '@app/modules/dynamic_attributes/widgets/user_attribute/assets';
	public $css = [
		'css/attribute.css'
	];
	public $js = [
		'js/attribute.js'
	];
	public $depends = [
		AppAsset::class
	];
}