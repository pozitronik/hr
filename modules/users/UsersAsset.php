<?php
declare(strict_types = 1);

namespace app\modules\users;

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
		$this->sourcePath = __DIR__.'/assets';
		$this->js = ['js/users.js'];
		$this->publishOptions = ['forceCopy' => YII_ENV_DEV];
		parent::init();
	}
}