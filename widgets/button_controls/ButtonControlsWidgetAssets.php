<?php
declare(strict_types = 1);

namespace app\widgets\button_controls;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class ButtonControlsWidgetAssets
 * @package app\components\button_controls
 */
class ButtonControlsWidgetAssets extends AssetBundle {
	public $sourcePath = '@app/widgets/button_controls/assets';
	public $css = [
		'css/button_controls.css'
	];
	public $js = [
		'js/button_controls.js'
	];
	public $depends = [
		AppAsset::class
	];

	public $publishOptions = [
		'forceCopy' => YII_ENV_DEV
	];
}