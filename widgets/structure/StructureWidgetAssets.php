<?php
declare(strict_types = 1);

namespace app\widgets\structure;

use yii\web\AssetBundle;
use app\assets\AppAsset;

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
		'js/sigma.js/plugins/sigma.parsers.json.min.js',
//		'js/sigma.js/plugins/sigma.exporters.svg.min.js',
		'js/sigma.js/plugins/sigma.layout.forceAtlas2.min.js',
		'js/sigma.js/plugins/sigma.layout.noverlap.min.js',
		'js/sigma.js/plugins/sigma.neo4j.cypher.min.js',
//		'js/sigma.js/plugins/sigma.parsers.gexf.min.js',
		'js/sigma.js/plugins/sigma.pathfinding.astar.min.js',
		'js/sigma.js/plugins/sigma.plugins.animate.min.js',
		'js/sigma.js/plugins/sigma.plugins.dragNodes.min.js',
		'js/sigma.js/plugins/sigma.plugins.filter.min.js',
		'js/sigma.js/plugins/sigma.plugins.neighborhoods.min.js',
		'js/sigma.js/plugins/sigma.plugins.relativeSize.min.js',
		'js/sigma.js/plugins/sigma.renderers.customEdgeShapes.min.js',
		'js/sigma.js/plugins/sigma.renderers.customShapes.min.js',
		'js/sigma.js/plugins/sigma.renderers.edgeDots.min.js',
		'js/sigma.js/plugins/sigma.renderers.edgeLabels.min.js',
		'js/sigma.js/plugins/sigma.renderers.parallelEdges.min.js',
		'js/sigma.js/plugins/sigma.renderers.snapshot.min.js',
		'js/sigma.js/plugins/sigma.statistics.HITS.min.js',
	];
	public $depends = [
		AppAsset::class
	];
}