<?php
declare(strict_types = 1);

namespace app\widgets\navbar;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class NavbarWidgetAssets
 * @package app\components\navbar
 */
class NavbarWidgetAssets extends AssetBundle {
	public $sourcePath = '@app/widgets/navbar/assets';
	public $css = [
		'css/navbar.css'
	];
	public $js = [
		'js/navbar.js'
	];
	public $depends = [
		AppAsset::class
	];
}








