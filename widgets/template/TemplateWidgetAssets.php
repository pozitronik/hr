<?php
declare(strict_types = 1);

namespace app\widgets\template;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class TemplateWidgetAssets
 * @package app\components\template
 */
class TemplateWidgetAssets extends AssetBundle {
	/**
	 * @inheritdoc
	 */
	public function init() {
		$this->depends = [AppAsset::class];
		$this->sourcePath = __DIR__.'/assets';
		$this->css = ['css/template.css'];
		$this->js = ['js/template.js'];
		$this->publishOptions = ['forceCopy' => YII_ENV_DEV];
		parent::init();
	}
}