<?php
declare(strict_types = 1);

namespace app\widgets\select_model;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class SelectModelWidgetAssets
 * @package app\components\select_model
 */
class SelectModelWidgetAssets extends AssetBundle {
	public $sourcePath = '@app/widgets/select_model/assets';
	public $css = [
		'css/select_model.css'
	];
	public $js = [
		'js/common.js',
		'js/select_model.js'
	];
	public $depends = [
		AppAsset::class
	];

	public $publishOptions = [
		'forceCopy' => YII_ENV_DEV
	];
}