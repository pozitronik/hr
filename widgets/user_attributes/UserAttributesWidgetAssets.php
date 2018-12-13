<?php
declare(strict_types = 1);

namespace app\widgets\user_attributes;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class UserAttributesWidgetAssets
 * @package app\widgets\attribute
 */
class UserAttributesWidgetAssets extends AssetBundle {
	public $sourcePath = '@app/widgets/user_attributes/assets';
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