<?php
declare(strict_types = 1);

namespace app\modules\groups\widgets\relation_type_select;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class RelationTypeSelectWidgetAssets
 * @package app\components\relation_type_select
 */
class RelationTypeSelectWidgetAssets extends AssetBundle {

	/**
	 * @inheritdoc
	 */
	public function init() {
		$this->depends = [AppAsset::class];
		$this->sourcePath = __DIR__.'/assets';
		$this->css = ['css/relation_type_select.css'];
		$this->js = ['js/relation_type_select.js'];
		$this->publishOptions = ['forceCopy' => YII_ENV_DEV];
		parent::init();
	}
}