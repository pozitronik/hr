<?php
declare(strict_types = 1);

namespace app\widgets\workgroup;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class WorkgroupWidgetAssets
 * @package app\components\workgroup
 */
class WorkgroupWidgetAssets extends AssetBundle {
	public $sourcePath = '@app/widgets/workgroup/assets';
	public $css = [
		'css/workgroup.css'
	];
	public $js = [
		'js/workgroup.js'
	];
	public $depends = [
		AppAsset::class
	];
}








