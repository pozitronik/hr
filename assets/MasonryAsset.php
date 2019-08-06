<?php
declare(strict_types = 1);

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Class MasonryAsset
 * @package app\assets
 */
class MasonryAsset extends AssetBundle {
	public $sourcePath = '@vendor/desandro/masonry/dist';

	public $js = [
		'masonry.pkgd.js',
//		'js/particles.js/particles.json'//config
	];

}
