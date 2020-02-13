<?php
declare(strict_types = 1);

namespace app\modules\targets\widgets\interval;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class IntervalWidgetAssets
 * @package app\components\interval
 */
class IntervalWidgetAssets extends AssetBundle {
	/**
	 * @inheritdoc
	 */
	public function init() {
		$this->depends = [AppAsset::class];
		$this->sourcePath = __DIR__.'/assets';
		$this->css = ['css/interval.css'];
		$this->js = ['js/interval.js'];
		$this->publishOptions = ['forceCopy' => YII_ENV_DEV];
		parent::init();
	}
}