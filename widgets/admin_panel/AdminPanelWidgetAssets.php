<?php
declare(strict_types = 1);

namespace app\widgets\admin_panel;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class AdminPanelWidgetAssets
 * @package app\components\admin_panel
 */
class AdminPanelWidgetAssets extends AssetBundle {
	/**
	 * @inheritdoc
	 */
	public function init() {
		$this->depends = [AppAsset::class];
		$this->sourcePath = __DIR__.'/assets';
		$this->css = ['css/admin_panel.css'];
		$this->js = ['js/admin_panel.js'];
		$this->publishOptions = ['forceCopy' => YII_ENV_DEV];
		parent::init();
	}
}








