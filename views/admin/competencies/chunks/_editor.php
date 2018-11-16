<?php
declare(strict_types = 1);

use yii\web\View;
use kartik\grid\GridView;
use yii\data\ArrayDataProvider;

/**
 * @var View $this
 */

?>


<?= GridView::widget([
	'dataProvider' => new ArrayDataProvider([
		'allModels' => [
			[
				'name' => 'test',
				'type' => 'int',
				'required' => false,
			]
		]
	]),
	'panel' => [
		'type' => GridView::TYPE_DEFAULT,
	],
	'toolbar' => false,
	'export' => false,
	'resizableColumns' => true,
	'responsive' => true,
	'columns' => [
		'name',
		'type',
		'required'
	]

]); ?>