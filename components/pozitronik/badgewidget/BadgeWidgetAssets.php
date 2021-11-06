<?php
declare(strict_types = 1);

namespace app\components\pozitronik\widgets;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class BadgeWidgetAssets
 */
class BadgeWidgetAssets extends AssetBundle {
	public $depends = [
		AppAsset::class
	];

	public $publishOptions = [
		'forceCopy' => YII_ENV_DEV
	];

	/**
	 * @inheritdoc
	 */
	public function init() {
		$this->sourcePath = __DIR__.'/assets';
		$this->css = ['css/badge.css'];
		parent::init();
	}
}