<?php
declare(strict_types = 1);

namespace app\modules\targets\assets;

use app\assets\AppAsset;
use yii\web\AssetBundle;

/**
 * Class TargetsAsset
 * @package app\modules\targets\assets
 */
class TargetsAsset extends AssetBundle {
	/**
	 * @inheritdoc
	 */
	public function init() {
		$this->depends = [AppAsset::class];
		$this->sourcePath = __DIR__.'/assets';
		$this->css = ['css/targets.cs'];
		$this->publishOptions = ['forceCopy' => YII_ENV_DEV];
		parent::init();
	}
}