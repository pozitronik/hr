<?php
declare(strict_types = 1);

namespace app\modules\targets\widgets\interval;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class IntervalWidgetAssets
 * @package app\components\interval
 */
class IntervalWidgetAssets extends AssetBundle {
	public $sourcePath = '@app/modules/targets/widgets/interval/assets';
	public $css = [
		'css/interval.css'
	];
	public $js = [
		'js/interval.js'
	];
	public $depends = [
		AppAsset::class
	];

	public $publishOptions = [
		'forceCopy' => YII_ENV_DEV
	];
}