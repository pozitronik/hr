<?php
declare(strict_types = 1);

namespace app\widgets\group_card;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class GroupCardWidgetAssets
 * @package app\components\group_card
 */
class GroupCardWidgetAssets extends AssetBundle {
	public $sourcePath = '@app/widgets/group_card/assets';
	public $css = [
		'css/group_card.css'
	];
	public $js = [
		'js/group_card.js'
	];
	public $depends = [
		AppAsset::class
	];

	public $publishOptions = [
		'forceCopy' => true
	];
}