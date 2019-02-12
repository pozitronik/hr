<?php
declare(strict_types = 1);

namespace app\modules\users\widgets\user;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class UserWidgetAssets
 * @package app\components\user
 */
class UserWidgetAssets extends AssetBundle {
	public $sourcePath = '@app/modules/users/widgets/user/assets';
	public $css = [
		'css/user.css'
	];
	public $js = [
		'js/user.js'
	];
	public $depends = [
		AppAsset::class
	];
}








