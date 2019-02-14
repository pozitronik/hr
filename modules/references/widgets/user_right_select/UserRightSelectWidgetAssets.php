<?php
declare(strict_types = 1);

namespace app\modules\references\widgets\user_right_select;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class UserRightSelectWidgetAssets
 * @package app\components\user_right_select
 */
class UserRightSelectWidgetAssets extends AssetBundle {
	public $sourcePath = '@app/modules/references/widgets/user_right_select/assets';
	public $css = [
		'css/user_right_select.css'
	];
	public $js = [
		'js/user_right_select.js'
	];
	public $depends = [
		AppAsset::class
	];
}








