<?php
declare(strict_types = 1);

namespace app\modules\users\widgets\roles_select;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class RolesSelectWidgetAssets
 * @package app\components\roles_select
 */
class RolesSelectWidgetAssets extends AssetBundle {

	/**
	 * @inheritdoc
	 */
	public function init() {
		$this->depends = [AppAsset::class];
		$this->sourcePath = __DIR__.'/assets';
		$this->css = ['css/roles_select.css'];
		$this->js = ['js/roles_select.js'];
		$this->publishOptions = ['forceCopy' => YII_ENV_DEV];
		parent::init();
	}
}