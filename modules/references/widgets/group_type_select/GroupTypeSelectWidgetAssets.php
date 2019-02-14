<?php
declare(strict_types = 1);

namespace app\modules\references\widgets\group_type_select;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class GroupTypeSelectWidgetAssets
 * @package app\components\group_type_select
 */
class GroupTypeSelectWidgetAssets extends AssetBundle {
	public $sourcePath = '@app/modules/references/widgets/group_type_select/assets';
	public $css = [
		'css/group_type_select.css'
	];
	public $js = [
		'js/group_type_select.js'
	];
	public $depends = [
		AppAsset::class
	];
}