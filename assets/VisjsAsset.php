<?php
declare(strict_types = 1);

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Class VisjsAsset
 * @package app\assets
 */
class VisjsAsset extends AssetBundle {
	public $sourcePath = '@js/vis.js';

	public $css = [
		'vis.min.css'
	];
	public $js = [
		'vis.min.js'
//		'js/particles.js/particles.json'//config
	];

}
