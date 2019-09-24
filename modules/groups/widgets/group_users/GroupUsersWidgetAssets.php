<?php
declare(strict_types = 1);

namespace app\modules\groups\widgets\group_users;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class GroupCardWidgetAssets
 * @package app\components\group_card
 */
class GroupUsersWidgetAssets extends AssetBundle {
	public $sourcePath = '@app/modules/groups/widgets/group_users/assets';
	public $css = [
		'css/group_users.css'
	];
	public $js = [
		'js/group_users.js'
	];
	public $depends = [
		AppAsset::class
	];

	public $publishOptions = [
		'forceCopy' => YII_ENV_DEV
	];
}