<?php
declare(strict_types = 1);

namespace app\modules\groups\widgets\group;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class GroupWidgetAssets
 * @package app\components\group
 */
class GroupWidgetAssets extends AssetBundle {
	/**
	 * @inheritdoc
	 */
	public function init() {
		$this->depends = [AppAsset::class];
		$this->sourcePath = __DIR__.'/assets';
		$this->css = ['css/group.css'];
		$this->js = ['js/group.js'];
		$this->publishOptions = ['forceCopy' => YII_ENV_DEV];
		parent::init();
	}
}








