<?php
declare(strict_types = 1);

namespace app\modules\users\assets;

use app\assets\AppAsset;
use yii\web\AssetBundle;

/**
 * Class UsersAsset
 * @package app\modules\users\assets
 */
class UsersAsset extends AssetBundle {
	/**
	 * @inheritdoc
	 */
	public function init() {
		$this->depends = [AppAsset::class];
		$this->sourcePath = __DIR__;
		$this->js = ['js/users.js'];
		$this->publishOptions = ['forceCopy' => YII_ENV_DEV];
		parent::init();
	}
}