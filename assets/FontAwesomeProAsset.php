<?php
declare(strict_types = 1);

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Class FontAwesomeAsset
 * @package app\assets
 */
class FontAwesomeProAsset extends AssetBundle {
	public $sourcePath = '@webroot/fonts/font-awesome/';
	public $baseUrl = '@web';

	public $css = [
		'css/all.css',
	];
}
