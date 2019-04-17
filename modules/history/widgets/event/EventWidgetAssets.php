<?php
declare(strict_types = 1);

namespace app\modules\history\widgets\event;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class EventWidgetAssets
 * @package app\components\event
 */
class EventWidgetAssets extends AssetBundle {
	public $sourcePath = '@app/modules/history/widgets/event/assets';
	public $css = [
		'css/event.css'
	];
	public $js = [
		'js/event.js'
	];
	public $depends = [
		AppAsset::class
	];
}