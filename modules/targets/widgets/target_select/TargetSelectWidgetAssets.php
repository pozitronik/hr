<?php
declare(strict_types = 1);

namespace app\modules\targets\widgets\target_select;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class TargetSelectWidgetAssets
 * @package app\components\target_select
 */
class TargetSelectWidgetAssets extends AssetBundle {
	/**
	 * @inheritdoc
	 */
	public function init() {
		$this->depends = [AppAsset::class];
		$this->sourcePath = __DIR__.'/assets';
		$this->css = ['css/target_select.css'];
		$this->js = ['js/target_select.js'];
		$this->publishOptions = ['forceCopy' => YII_ENV_DEV];
		parent::init();
	}
}








