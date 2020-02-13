<?php
declare(strict_types = 1);

namespace app\modules\dynamic_attributes\widgets\attribute_field_dictionary;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class DictionaryWidgetAssets
 * @package app\components\dictionary
 */
class DictionaryWidgetAssets extends AssetBundle {
	/**
	 * @inheritdoc
	 */
	public function init() {
		$this->depends = [AppAsset::class];
		$this->sourcePath = __DIR__.'/assets';
		$this->css = ['css/dictionary.css'];
		$this->js = ['js/dictionary.js'];
		$this->publishOptions = ['forceCopy' => YII_ENV_DEV];
		parent::init();
	}
}