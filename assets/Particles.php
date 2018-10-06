<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Class Particles
 * @package app\assets
 */
class Particles extends AssetBundle {
	public $sourcePath = '@vendor/npm-asset/particles.js';

	public $js = [
		'particles.js',
//		'js/particles.js/particles.json'//config
	];

}
