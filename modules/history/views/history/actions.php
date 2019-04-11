<?php
declare(strict_types = 1);

/**
 * @var HistoryEventAction[] $actions
 */

use app\modules\history\models\HistoryEventAction;
use kartik\grid\DataColumn;
use kartik\grid\GridView;
use yii\data\ArrayDataProvider;
use yii\i18n\Formatter;

?>

<?= GridView::widget([
	'dataProvider' => new ArrayDataProvider([
		'allModels' => $actions,
		'sort' => [
			'attributes' => ['type', 'attributeName']
		]
	]),
	'summary' => false,
	'formatter' => [
		'class' => Formatter::class,
		'nullDisplay' => ''
	],
	'columns' => [
		[
			'class' => DataColumn::class,
			'attribute' => 'typeName',
			'group' => true,
			'width' => '10%'
		],
		[
			'class' => DataColumn::class,
			'attribute' => 'attributeName',
			'width' => '20%'
		],
		[
			'class' => DataColumn::class,
			'attribute' => 'attributeOldValue',
			'width' => '25%'
		],
		[
			'class' => DataColumn::class,
			'attribute' => 'attributeNewValue',
			'width' => '25%'
		]
	]
]); ?>
