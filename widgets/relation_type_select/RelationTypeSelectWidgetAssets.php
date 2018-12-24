<?php
declare(strict_types = 1);

namespace app\widgets\relation_type_select;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class RelationTypeSelectWidgetAssets
 * @package app\components\relation_type_select
 */
class RelationTypeSelectWidgetAssets extends AssetBundle {
	public $sourcePath = '@app/widgets/relation_type_select/assets';
	public $css = [
		'css/relation_type_select.css'
	];
	public $js = [
		'js/relation_type_select.js'
	];
	public $depends = [
		AppAsset::class
	];
}