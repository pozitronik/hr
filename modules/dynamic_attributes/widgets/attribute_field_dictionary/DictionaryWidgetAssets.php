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
	public $sourcePath = '@app/modules/dynamic_attributes/widgets/attribute_field_dictionary/assets';
	public $css = [
		'css/dictionary.css'
	];
	public $js = [
		'js/dictionary.js'
	];
	public $depends = [
		AppAsset::class
	];
}