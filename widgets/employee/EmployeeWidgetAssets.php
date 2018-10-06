<?php
declare(strict_types = 1);

namespace app\widgets\employee;

use yii\web\AssetBundle;
use app\assets\AppAsset;

/**
 * Class EmployeeWidgetAssets
 * @package app\components\employee
 */
class EmployeeWidgetAssets extends AssetBundle {
	public $sourcePath = '@app/widgets/employee/assets';
	public $css = [
		'css/employee.css'
	];
	public $js = [
		'js/employee.js'
	];
	public $depends = [
		AppAsset::class
	];
}








