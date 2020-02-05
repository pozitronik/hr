<?php
declare(strict_types = 1);

namespace app\modules\graph\assets;

use yii\web\AssetBundle;

/**
 * Class VisjsAssetTargets
 * @package app\modules\graph\assets
 */
class VisjsAssetTargets extends AssetBundle {
	public $sourcePath = '@app/modules/graph/assets';

	public $css = [
		'js/vis.js/vis.css',
		'css/structure.css'
	];
	public $js = [
		'js/common.js',
		'js/vis.js/vis.js',
		'js/tree_init_targets.js'
	];

	public $publishOptions = [
		'forceCopy' => YII_ENV_DEV
	];
}