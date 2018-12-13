<?php
declare(strict_types = 1);

namespace app\assets;

use yii\web\AssetBundle;
use yii\web\YiiAsset;
use yii\bootstrap\BootstrapAsset;

/**
 * Class LoginAssetAsset
 * @package app\assets
 */
class LoginAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		'templates/nifty/css/nifty.min.css',
		'css/login.css'
	];


	public $depends = [
		AppAsset::class,
		YiiAsset::class,
		BootstrapAsset::class,
		ParticlesAsset::class
	];
}
