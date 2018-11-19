<?php
declare(strict_types = 1);

namespace app\widgets\competency;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class CompetencyWidgetAssets
 * @package app\components\competency
 */
class CompetencyWidgetAssets extends AssetBundle {
	public $sourcePath = '@app/widgets/competency/assets';
	public $css = [
		'css/competency.css'
	];
	public $js = [
		'js/competency.js'
	];
	public $depends = [
		AppAsset::class
	];
}