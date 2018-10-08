<?php
declare(strict_types = 1);

namespace app\widgets\admin_panel;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class AdminPanelWidgetAssets
 * @package app\components\admin_panel
 */
class AdminPanelWidgetAssets extends AssetBundle {
	public $sourcePath = '@app/widgets/admin_panel/assets';
	public $css = [
		'css/admin_panel.css'
	];
	public $js = [
		'js/admin_panel.js'
	];
	public $depends = [
		AppAsset::class
	];
}








