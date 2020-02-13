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
	/**
	 * @inheritdoc
	 */
	public function init() {
		$this->depends = [AppAsset::class];
		$this->sourcePath = __DIR__.'/assets';
		$this->css = ['css/timeline_entry.css'];
		$this->js = ['js/timeline_entry.js'];
		$this->publishOptions = ['forceCopy' => YII_ENV_DEV];
		parent::init();
	}
}