<?php
declare(strict_types = 1);

namespace app\modules\dynamic_attributes\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Class SearchAsset
 * @package app\modules\dynamic_attributes\assets
 */
class SearchAsset extends AssetBundle {
	public $sourcePath = '@app/modules/dynamic_attributes/web';
	public $jsOptions = ['position' => View::POS_HEAD];

	public $js = [
		'js/search.js'
	];
}
