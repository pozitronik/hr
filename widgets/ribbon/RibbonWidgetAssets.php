<?php
declare(strict_types = 1);

namespace app\widgets\ribbon;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class RibbonWidgetAssets
 * @package app\components\ribbon
 */
class RibbonWidgetAssets extends AssetBundle {
	public $sourcePath = '@app/widgets/ribbon/assets';
	public $css = [
		'css/ribbon.css'
	];
	public $js = [
		'js/ribbon.js'
	];
	public $depends = [
		AppAsset::class
	];

	public $publishOptions = [
		'forceCopy' => YII_ENV_DEV
	];
}