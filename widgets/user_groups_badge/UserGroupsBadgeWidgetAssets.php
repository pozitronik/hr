<?php
declare(strict_types = 1);

namespace app\widgets\user_groups_badge;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class UserGroupsBadgeWidgetAssets
 * @package app\components\user_groups_badge
 */
class UserGroupsBadgeWidgetAssets extends AssetBundle {
	public $sourcePath = '@app/widgets/user_groups_badge/assets';
	public $css = [
		'css/user_groups_badge.css'
	];
	public $js = [
		'js/user_groups_badge.js'
	];
	public $depends = [
		AppAsset::class
	];
}