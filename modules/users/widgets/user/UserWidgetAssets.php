<?php
declare(strict_types = 1);

namespace app\modules\users\widgets\user;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class UserWidgetAssets
 * @package app\components\user
 */
class UserWidgetAssets extends AssetBundle {
	/**
	 * @inheritdoc
	 */
	public function init() {
		$this->depends = [AppAsset::class];
		$this->sourcePath = __DIR__.'/assets';
		$this->css = ['css/user.css'];
		$this->js = ['js/user.js'];
		$this->publishOptions = ['forceCopy' => YII_ENV_DEV];
		parent::init();
	}
}








