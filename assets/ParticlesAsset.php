<?php
declare(strict_types = 1);

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Class Particles
 * @package app\assets
 */
class ParticlesAsset extends AssetBundle {
	public $sourcePath = '@vendor/npm-asset/particles.js';

	public $js = [
		'particles.js',
//		'js/particles.js/particles.json'//config
	];

}
