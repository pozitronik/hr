<?php
declare(strict_types = 1);

namespace app\modules\groups\assets;

use yii\web\AssetBundle;

/**
 * Class GroupsAsset
 * @package app\modules\groups\assets
 */
class GroupsAsset extends AssetBundle {
	public $sourcePath = '@app/modules/groups/assets';

	public $js = [
		'js/groups.js'
	];

	public $publishOptions = [
		'forceCopy' => YII_ENV_DEV
	];
}