<?php
declare(strict_types = 1);

namespace app\widgets\controller;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class ControllerWidgetAssets
 * @package app\components\controller
 */
class ControllerWidgetAssets extends AssetBundle {
	public $sourcePath = '@app/widgets/controller/assets';
	public $css = [
		'css/controller.css'
	];
	public $js = [
		'js/controller.js'
	];
	public $depends = [
		AppAsset::class
	];
}








