<?php
declare(strict_types = 1);

namespace app\widgets\score;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class ScoreWidgetAssets
 * @package app\components\score
 */
class ScoreWidgetAssets extends AssetBundle {
	public $sourcePath = '@app/widgets/score/assets';
	public $css = [
		'css/score.css'
	];
	public $js = [
		'js/score.js'
	];
	public $depends = [
		AppAsset::class
	];
}