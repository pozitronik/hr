<?php
declare(strict_types = 1);

namespace app\modules\groups\widgets\group_leaders;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class GroupLeadersWidgetAssets
 * @package app\modules\groups\widgets\group_leaders
 */
class GroupLeadersWidgetAssets extends AssetBundle {
	/**
	 * @inheritdoc
	 */
	public function init() {
		$this->depends = [AppAsset::class];
		$this->sourcePath = __DIR__.'/assets';
		$this->css = ['css/group_leaders.css'];
		$this->js = ['js/group_leaders.js'];
		$this->publishOptions = ['forceCopy' => YII_ENV_DEV];
		parent::init();
	}
}