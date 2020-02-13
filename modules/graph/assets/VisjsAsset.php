<?php
declare(strict_types = 1);

namespace app\modules\graph\assets;

use app\assets\AppAsset;
use yii\web\AssetBundle;

/**
 * Class VisjsAsset
 * @package app\modules\graph\assets
 */
class VisjsAsset extends AssetBundle {
	/**
	 * @inheritdoc
	 */
	public function init() {
		$this->depends = [AppAsset::class];
		$this->sourcePath = __DIR__.'/assets';
		$this->css = [
			'js/vis.js/vis.css',
			'css/structure.css'
		];
		$this->js = [
			'js/common.js',
			'js/vis.js/vis.js',
			'js/tree_init.js'
		];
		$this->publishOptions = ['forceCopy' => YII_ENV_DEV];
		parent::init();
	}
}