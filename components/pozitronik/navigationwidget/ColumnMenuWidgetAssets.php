<?php
declare(strict_types = 1);

namespace app\components\pozitronik\navigationwidget;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class NavigationMenuWidgetAssets
 * @package app\components\navigation_menu
 */
class ColumnMenuWidgetAssets extends AssetBundle {
	/**
	 * @inheritdoc
	 */
	public function init() {
		$this->depends = [AppAsset::class];
		$this->sourcePath = __DIR__.'/assets';
		$this->js = ['js/navigation_column_menu.js'];
		$this->publishOptions = ['forceCopy' => YII_ENV_DEV];
		parent::init();
	}
}