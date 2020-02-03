<?php
declare(strict_types = 1);

namespace app\modules\targets\assets;

use yii\web\AssetBundle;

/**
 * Class TargetsAsset
 * @package app\modules\targets\assets
 */
class TargetsAsset extends AssetBundle {
	public $sourcePath = '@app/modules/targets/assets';

	public $css = [
		'css/targets.css'
	];
	public $publishOptions = [
		'forceCopy' => YII_ENV_DEV
	];
}