<?php
declare(strict_types = 1);

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Class IsotopeAsset
 * @package app\assets
 */
class IsotopeAsset extends AssetBundle {
	public $sourcePath = '@vendor/metafizzy/isotope/dist';

	public $js = [
		'isotope.pkgd.js'
	];

}
