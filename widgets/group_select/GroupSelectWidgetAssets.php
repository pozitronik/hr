<?php
declare(strict_types = 1);

namespace app\widgets\group_select;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class GroupSelectWidgetAssets
 * @package app\components\group_select
 */
class GroupSelectWidgetAssets extends AssetBundle {
	public $sourcePath = '@app/widgets/group_select/assets';
	public $css = [
		'css/group_select.css'
	];
	public $js = [
		'js/group_select.js'
	];
	public $depends = [
		AppAsset::class
	];
}








