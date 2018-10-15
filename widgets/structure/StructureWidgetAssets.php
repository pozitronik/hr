<?php
declare(strict_types = 1);

namespace app\widgets\structure;

use yii\web\AssetBundle;
use app\assets\AppAsset;
use yii\web\View;

/**
 * Class StructureWidgetAssets
 * @package app\components\structure
 */
class StructureWidgetAssets extends AssetBundle {
	public $sourcePath = '@app/widgets/structure/assets';
	public $css = [
		'css/structure.css'
	];
	public $js = [
		'js/sigma.js/sigma.min.js',
		'js/sigma.js/plugins/sigma.renderers.parallelEdges/utils.js',
		'js/sigma.js/plugins/sigma.renderers.parallelEdges/sigma.canvas.edges.curve.js',
		'js/sigma.js/plugins/sigma.renderers.parallelEdges/sigma.canvas.edges.curvedArrow.js',
		'js/sigma.js/plugins/sigma.renderers.parallelEdges/sigma.canvas.edgehovers.curve.js',
		'js/sigma.js/plugins/sigma.renderers.parallelEdges/sigma.canvas.edgehovers.curvedArrow.js',
		'js/sigma.js/plugins/sigma.renderers.edgeLabels/settings.js',
		'js/sigma.js/plugins/sigma.renderers.edgeLabels/sigma.canvas.edges.labels.def.js',
		'js/sigma.js/plugins/sigma.renderers.edgeLabels/sigma.canvas.edges.labels.curve.js',
		'js/sigma.js/plugins/sigma.renderers.edgeLabels/sigma.canvas.edges.labels.curvedArrow.js',
		'js/sigma.js/plugins/sigma.parsers.json/sigma.parsers.json.js',
	];
	public $depends = [
		AppAsset::class
	];

	public $jsOptions = [
		'position' => View::POS_HEAD
	];
}