<?php
declare(strict_types = 1);

namespace app\modules\dynamic_attributes\widgets\radar;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class RadarWidgetAssets
 * @package app\components\radar
 */
class RadarWidgetAssets extends AssetBundle {
	/**
	 * @inheritdoc
	 */
	public function init() {
		$this->depends = [AppAsset::class];
		$this->sourcePath = __DIR__.'/assets';
		$this->css = ['css/radar.css'];
		$this->js = ['js/radar.js'];
		$this->publishOptions = ['forceCopy' => YII_ENV_DEV];
		parent::init();
	}
}