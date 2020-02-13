<?php
declare(strict_types = 1);

namespace app\widgets\user_card;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class UserCardWidgetAssets
 * @package app\components\user_card
 */
class UserCardWidgetAssets extends AssetBundle {
	/**
	 * @inheritdoc
	 */
	public function init() {
		$this->depends = [AppAsset::class];
		$this->sourcePath = __DIR__.'/assets';
		$this->css = ['css/user_card.css'];
		$this->js = ['js/user_card.js'];
		$this->publishOptions = ['forceCopy' => YII_ENV_DEV];
		parent::init();
	}
}