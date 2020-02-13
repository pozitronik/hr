<?php
declare(strict_types = 1);

namespace app\modules\users\widgets\user_select;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class UserSelectWidgetAssets
 * @package app\components\user_select
 */
class UserSelectWidgetAssets extends AssetBundle {
	/**
	 * @inheritdoc
	 */
	public function init() {
		$this->depends = [AppAsset::class];
		$this->sourcePath = __DIR__.'/assets';
		$this->css = ['css/user_select.css'];
		$this->js = ['js/user_select.js'];
		$this->publishOptions = ['forceCopy' => YII_ENV_DEV];
		parent::init();
	}
}








