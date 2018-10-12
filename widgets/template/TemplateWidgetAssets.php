<?php
declare(strict_types = 1);

namespace app\widgets\template;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class TemplateWidgetAssets
 * @package app\components\template
 */
class TemplateWidgetAssets extends AssetBundle {
	public $sourcePath = '@app/widgets/template/assets';
	public $css = [
		'css/template.css'
	];
	public $js = [
		'js/template.js'
	];
	public $depends = [
		AppAsset::class
	];
}