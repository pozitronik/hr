<?php
declare(strict_types = 1);

namespace app\widgets\template;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class GroupSelectWidgetAssets
 * @package app\components\template
 */
class TemplateWidgetAssets extends AssetBundle {
	public $sourcePath = '@app/widgets/template/assets';
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








