<?php
declare(strict_types = 1);

namespace app\modules\history\widgets\timeline_entry;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class TimelineWidgetAssets
 * @package app\components\timeline
 */
class TimelineEntryWidgetAssets extends AssetBundle {
	public $sourcePath = '@app/modules/history/widgets/timeline_entry/assets';
	public $css = [
		'css/timeline_entry.css'
	];
	public $js = [
		'js/timeline_entry.js'
	];
	public $depends = [
		AppAsset::class
	];
}