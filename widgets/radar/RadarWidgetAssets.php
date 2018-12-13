<?php
declare(strict_types = 1);

namespace app\widgets\radar;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class RadarWidgetAssets
 * @package app\components\radar
 */
class RadarWidgetAssets extends AssetBundle {
	public $sourcePath = '@app/widgets/radar/assets';
	public $css = [
		'css/radar.css'
	];
	public $js = [
		'js/radar.js'
	];
	public $depends = [
		AppAsset::class
	];
}