<?php
declare(strict_types = 1);

namespace app\modules\references\widgets\roles_select;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class RolesSelectWidgetAssets
 * @package app\components\roles_select
 */
class RolesSelectWidgetAssets extends AssetBundle {
	public $sourcePath = '@app/modules/references/widgets/roles_select/assets';
	public $css = [
		'css/roles_select.css'
	];
	public $js = [
		'js/roles_select.js'
	];
	public $depends = [
		AppAsset::class
	];
}