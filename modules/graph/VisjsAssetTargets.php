<?php
declare(strict_types = 1);

namespace app\modules\graph\assets;

use yii\web\AssetBundle;

/**
 * Class VisjsAssetTargets
 * @package app\modules\graph\assets
 */
class VisjsAssetTargets extends AssetBundle {
	/**
	 * @inheritdoc
	 */
	public function init() {
		$this->sourcePath = __DIR__.'/assets';
		$this->css = [
			'js/vis.js/vis.css',
			'css/structure.css'
		];
		$this->js = [
			'js/common.js',
			'js/vis.js/vis.js',
			'js/tree_init_targets.js'
		];
		$this->publishOptions = ['forceCopy' => YII_ENV_DEV];
		parent::init();
	}
}