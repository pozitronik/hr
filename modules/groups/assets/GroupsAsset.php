<?php
declare(strict_types = 1);

namespace app\modules\groups\assets;

use app\assets\AppAsset;
use yii\web\AssetBundle;

/**
 * Class GroupsAsset
 * @package app\modules\groups\assets
 */
class GroupsAsset extends AssetBundle {
	/**
	 * @inheritdoc
	 */
	public function init() {
		$this->depends = [AppAsset::class];
		$this->sourcePath = __DIR__.'/assets';
		$this->js = ['js/groups.js'];
		$this->publishOptions = ['forceCopy' => YII_ENV_DEV];
		parent::init();
	}
}