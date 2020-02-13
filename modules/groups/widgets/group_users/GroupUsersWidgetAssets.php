<?php
declare(strict_types = 1);

namespace app\modules\groups\widgets\group_users;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class GroupCardWidgetAssets
 * @package app\components\group_card
 */
class GroupUsersWidgetAssets extends AssetBundle {

	/**
	 * @inheritdoc
	 */
	public function init() {
		$this->depends = [AppAsset::class];
		$this->sourcePath = __DIR__.'/assets';
		$this->css = ['css/group_users.css'];
		$this->js = ['js/group_users.js'];
		$this->publishOptions = ['forceCopy' => YII_ENV_DEV];
		parent::init();
	}
}