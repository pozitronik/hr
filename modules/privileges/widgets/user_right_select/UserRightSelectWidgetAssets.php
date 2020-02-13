<?php
declare(strict_types = 1);

namespace app\modules\privileges\widgets\user_right_select;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class UserRightSelectWidgetAssets
 * @package app\components\user_right_select
 */
class UserRightSelectWidgetAssets extends AssetBundle {
	/**
	 * @inheritdoc
	 */
	public function init() {
		$this->depends = [AppAsset::class];
		$this->sourcePath = __DIR__.'/assets';
		$this->css = ['css/user_right_select.css'];
		$this->js = ['js/user_right_select.js'];
		$this->publishOptions = ['forceCopy' => YII_ENV_DEV];
		parent::init();
	}
}








