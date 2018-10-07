<?php
declare(strict_types = 1);

namespace app\widgets\admin_control;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class Admin_controlWidgetAssets
 * @package app\components\admin_control
 */
class Admin_controlWidgetAssets extends AssetBundle {
	public $sourcePath = '@app/widgets/admin_control/assets';
	public $css = [
		'css/admin_control.css'
	];
	public $js = [
		'js/admin_control.js'
	];
	public $depends = [
		AppAsset::class
	];
}








