<?php
declare(strict_types = 1);

namespace app\widgets\user_card;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class UserCardWidgetAssets
 * @package app\components\user_card
 */
class UserCardWidgetAssets extends AssetBundle {
	public $sourcePath = '@app/widgets/user_card/assets';
	public $css = [
		'css/user_card.css'
	];
	public $js = [
		'js/user_card.js'
	];
	public $depends = [
		AppAsset::class
	];

	public $publishOptions = [
		'forceCopy' => YII_ENV_DEV
	];
}