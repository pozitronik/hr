<?php
declare(strict_types = 1);

namespace app\modules\groups\widgets\group_select;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class GroupSelectWidgetAssets
 * @package app\components\group_select
 */
class GroupSelectWidgetAssets extends AssetBundle {

	/**
	 * @inheritdoc
	 */
	public function init() {
		$this->depends = [AppAsset::class];
		$this->sourcePath = __DIR__.'/assets';
		$this->css = ['css/group_select.css'];
		$this->js = ['js/group_select.js'];
		$this->publishOptions = ['forceCopy' => YII_ENV_DEV];
		parent::init();
	}
}








