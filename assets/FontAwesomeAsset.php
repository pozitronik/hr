<?php
declare(strict_types = 1);

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Class FontAwesomeAsset
 * @package app\assets
 */
class FontAwesomeAsset extends AssetBundle {
	public $sourcePath = '@vendor/components/font-awesome/';
//	public $sourcePath = '@bower/font-awesome';
	public $baseUrl = '@web';

	public $css = [
		'css/fontawesome-all.min.css',
	];
}
