<?php
declare(strict_types = 1);

namespace app\modules\targets\widgets\target_select;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class TargetSelectWidgetAssets
 * @package app\components\target_select
 */
class TargetSelectWidgetAssets extends AssetBundle {
	public $sourcePath = '@app/modules/targets/widgets/target_select/assets';
	public $css = [
		'css/target_select.css'
	];
	public $js = [
		'js/target_select.js'
	];
	public $depends = [
		AppAsset::class
	];

	public $publishOptions = [
		'forceCopy' => YII_ENV_DEV
	];
}








