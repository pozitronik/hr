<?php
declare(strict_types = 1);

namespace app\widgets\structure;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class StructureWidgetAssets
 * @package app\components\structure
 */
class StructureWidgetAssets extends AssetBundle {
	public $sourcePath = '@app/widgets/structure/assets';
	public $css = [
		'css/structure.css'
	];
	public $js = [
		'js/sigma.js/sigma.min.js',
		'js/structure.js'
	];
	public $depends = [
		AppAsset::class
	];
}