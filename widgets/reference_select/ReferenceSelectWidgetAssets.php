<?php
declare(strict_types = 1);

namespace app\widgets\reference_select;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class ReferenceSelectWidgetAssets
 * @package app\components\reference_select
 */
class ReferenceSelectWidgetAssets extends AssetBundle {
	public $sourcePath = '@app/widgets/reference_select/assets';
	public $css = [
		'css/reference_select.css'
	];
	public $js = [
		'js/reference_select.js'
	];
	public $depends = [
		AppAsset::class
	];
}