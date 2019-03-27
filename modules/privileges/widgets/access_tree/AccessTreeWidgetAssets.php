<?php
declare(strict_types = 1);

namespace app\modules\privileges\widgets\access_tree;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class AccessTreeWidgetAssets
 * @package app\components\access_tree
 */
class AccessTreeWidgetAssets extends AssetBundle {
	public $sourcePath = '@app/modules/privileges/widgets/access_tree/assets';
	public $css = [
		'css/access_tree.css'
	];
	public $js = [
		'js/access_tree.js'
	];
	public $depends = [
		AppAsset::class
	];
}