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
	public $sourcePath = '@app/modules/users/widgets/bookmarks/assets';
	public $css = [
		'css/bookmarks.css'
	];
	public $js = [
		'js/bookmarks.js'
	];
	public $depends = [
		AppAsset::class
	];
}