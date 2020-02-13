<?php
declare(strict_types = 1);

namespace app\modules\users\widgets\bookmarks;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class BookmarksWidgetAssets
 * @package app\components\bookmarks
 */
class BookmarksWidgetAssets extends AssetBundle {
	/**
	 * @inheritdoc
	 */
	public function init() {
		$this->depends = [AppAsset::class];
		$this->sourcePath = __DIR__.'/assets';
		$this->css = ['css/bookmarks.css'];
		$this->js = ['js/bookmarks.js'];
		$this->publishOptions = ['forceCopy' => YII_ENV_DEV];
		parent::init();
	}
}