<?php
declare(strict_types = 1);

namespace app\widgets\badge;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class BadgeWidgetAssets
 * @package app\components\badge
 */
class BadgeWidgetAssets extends AssetBundle {
	public $sourcePath = '@app/widgets/badge/assets';
	public $css = [
		'css/badge.css'
	];
	public $js = [
		'js/badge.js'
	];
	public $depends = [
		AppAsset::class
	];
}