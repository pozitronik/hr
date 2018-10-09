<?php
declare(strict_types = 1);

namespace app\widgets\group;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class GroupWidgetAssets
 * @package app\components\group
 */
class GroupWidgetAssets extends AssetBundle {
	public $sourcePath = '@app/widgets/group/assets';
	public $css = [
		'css/group.css'
	];
	public $js = [
		'js/group.js'
	];
	public $depends = [
		AppAsset::class
	];
}








