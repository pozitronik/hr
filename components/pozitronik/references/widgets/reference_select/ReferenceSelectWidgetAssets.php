<?php
declare(strict_types = 1);

namespace app\components\pozitronik\references\widgets\reference_select;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class BaseReferenceSelectWidgetAssets
 * @package app\modules\references\widgets\reference_select
 */
class ReferenceSelectWidgetAssets extends AssetBundle {

	/**
	 * @inheritdoc
	 */
	public function init() {
		$this->depends = [AppAsset::class];
		$this->sourcePath = __DIR__.'/assets';
		$this->css = ['css/reference_select.css'];
		$this->js = ['js/reference_select.js'];
		$this->publishOptions = ['forceCopy' => YII_ENV_DEV];
		parent::init();
	}
}