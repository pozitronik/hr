<?php
declare(strict_types = 1);

namespace app\modules\dynamic_attributes\widgets\attribute_field_score;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class ScoreWidgetAssets
 * @package app\components\score
 */
class ScoreWidgetAssets extends AssetBundle {
	/**
	 * @inheritdoc
	 */
	public function init() {
		$this->depends = [AppAsset::class];
		$this->sourcePath = __DIR__.'/assets';
		$this->css = ['css/score.css'];
		$this->js = ['js/score.js'];
		$this->publishOptions = ['forceCopy' => YII_ENV_DEV];
		parent::init();
	}
}