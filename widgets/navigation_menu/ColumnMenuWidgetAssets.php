<?php
declare(strict_types = 1);

namespace app\widgets\navigation_menu;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class NavigationMenuWidgetAssets
 * @package app\components\navigation_menu
 */
class ColumnMenuWidgetAssets extends AssetBundle {
	public $sourcePath = '@app/widgets/navigation_menu/assets';
	public $js = [
		'js/navigation_column_menu.js'
	];
	public $depends = [
		AppAsset::class
	];
}