<?php
declare(strict_types = 1);

namespace app\widgets\search;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class SearchWidgetAssets
 * @package app\components\search
 */
class SearchWidgetAssets extends AssetBundle {
	public $sourcePath = '@app/widgets/search/assets';
	public $css = [
		'css/search.css'
	];
	public $js = [
		'js/search.js'
	];
	public $depends = [
		AppAsset::class
	];

	public $publishOptions = [
		'forceCopy' => YII_ENV_DEV
	];
}