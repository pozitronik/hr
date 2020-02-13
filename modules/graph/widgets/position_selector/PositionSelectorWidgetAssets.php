<?php
declare(strict_types = 1);

namespace app\modules\graph\widgets\position_selector;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class PositionSelectorWidgetAssets
 * @package app\components\position_selector
 */
class PositionSelectorWidgetAssets extends AssetBundle {
	/**
	 * @inheritdoc
	 */
	public function init() {
		$this->depends = [AppAsset::class];
		$this->sourcePath = __DIR__.'/assets';
		$this->css = ['css/position_selector.css'];
		$this->js = ['js/position_selector.js'];
		$this->publishOptions = ['forceCopy' => YII_ENV_DEV];
		parent::init();
	}
}