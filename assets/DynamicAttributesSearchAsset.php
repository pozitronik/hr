<?php
declare(strict_types = 1);

namespace app\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Class Particles
 * @package app\assets
 */
class DynamicAttributesSearchAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $sourcePath = '@web';
	public $jsOptions = ['position' => View::POS_HEAD];

	public $js = [
		'js/dynamic_attributes_search/search.js'
	];

}
