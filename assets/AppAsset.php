<?php
declare(strict_types = 1);
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;
use yii\web\YiiAsset;
use yii\bootstrap\BootstrapPluginAsset;

/**
 * Class AppAsset
 * @package app\assets
 */
class AppAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		'templates/nifty/css/nifty.min.css',//Nifty
		'css/site.css'

	];
	public $js = [
		'templates/nifty/js/nifty.min.js',//Nifty
	];

	public $depends = [
		YiiAsset::class,
		BootstrapPluginAsset::class,
		FontAwesomeProAsset::class
	];
}
