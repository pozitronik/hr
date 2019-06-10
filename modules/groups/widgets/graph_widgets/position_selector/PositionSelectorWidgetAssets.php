<?php
declare(strict_types = 1);

namespace app\modules\groups\widgets\graph_widgets\position_selector;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class PositionSelectorWidgetAssets
 * @package app\components\position_selector
 */
class PositionSelectorWidgetAssets extends AssetBundle {
	public $sourcePath = '@app/modules/groups/widgets/graph_widgets/position_selector/assets';
	public $css = [
		'css/position_selector.css'
	];
	public $js = [
		'js/position_selector.js'
	];
	public $depends = [
		AppAsset::class
	];

	public $publishOptions = [
		'forceCopy' => true
	];
}