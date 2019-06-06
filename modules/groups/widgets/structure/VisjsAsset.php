<?php
declare(strict_types = 1);

namespace app\modules\groups\widgets\structure;

use yii\web\AssetBundle;

/**
 * Class VisjsAsset
 * @package app\assets
 */
class VisjsAsset extends AssetBundle {
	public $sourcePath = '@app/modules/groups/widgets/structure/assets';

	public $css = [
		'js/vis.js/vis.css',
		'css/structure.css'
	];
	public $js = [
		'js/common.js',
		'js/vis.js/vis.js',
		'js/tree_init.js'
//		'js/particles.js/particles.json'//config
	];

	public $publishOptions = [
		'forceCopy' => false
	];

}
