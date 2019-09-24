<?php
declare(strict_types = 1);

namespace app\modules\groups\widgets\group_leaders;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class GroupLeadersWidgetAssets
 * @package app\modules\groups\widgets\group_leaders
 */
class GroupLeadersWidgetAssets extends AssetBundle {
	public $sourcePath = '@app/modules/groups/widgets/group_leaders/assets';
	public $css = [
		'css/group_leaders.css'
	];
	public $js = [
		'js/group_leaders.js'
	];
	public $depends = [
		AppAsset::class
	];

	public $publishOptions = [
		'forceCopy' => YII_ENV_DEV
	];
}