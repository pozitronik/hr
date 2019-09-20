<?php
declare(strict_types = 1);

namespace app\modules\users\assets;

use yii\web\AssetBundle;

/**
 * Class UsersAsset
 * @package app\modules\users\assets
 */
class UsersAsset extends AssetBundle {
	public $sourcePath = '@app/modules/users/assets';

	public $css = [
	];
	public $js = [
		'js/users.js',
	];

	public $publishOptions = [
		'forceCopy' => YII_ENV_DEV
	];
}