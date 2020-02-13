<?php
declare(strict_types = 1);

namespace app\modules\groups\widgets\group_type_select;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class GroupTypeSelectWidgetAssets
 * @package app\components\group_type_select
 */
class GroupTypeSelectWidgetAssets extends AssetBundle {
	/**
	 * @inheritdoc
	 */
	public function init() {
		$this->depends = [AppAsset::class];
		$this->sourcePath = __DIR__.'/assets';
		$this->css = ['css/group_type_select.css'];
		$this->js = ['js/group_type_select.js'];
		$this->publishOptions = ['forceCopy' => YII_ENV_DEV];
		parent::init();
	}
}