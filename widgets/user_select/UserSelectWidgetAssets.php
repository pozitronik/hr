<?php
declare(strict_types = 1);

namespace app\widgets\user_select;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class UserSelectWidgetAssets
 * @package app\components\user_select
 */
class UserSelectWidgetAssets extends AssetBundle {
	public $sourcePath = '@app/widgets/user_select/assets';
	public $css = [
		'css/user_select.css'
	];
	public $js = [
		'js/user_select.js'
	];
	public $depends = [
		AppAsset::class
	];
}








