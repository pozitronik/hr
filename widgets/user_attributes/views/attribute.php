<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var DataProviderInterface $widgetDataProvider
 * @var boolean $show_category
 */

use app\helpers\ArrayHelper;
use app\models\dynamic_attributes\DynamicAttributeProperty;
use app\widgets\score\ScoreWidget;
use yii\data\DataProviderInterface;
use yii\web\View;
use kartik\grid\GridView;

?>

<?= GridView::widget([
	'dataProvider' => $widgetDataProvider,
	'panel' => false,
	'summary' => false,
	'headerRowOptions' => [
		'style' => 'display:none'
	],
	'toolbar' => false,
	'export' => false,
	'resizableColumns' => false,
	'responsive' => true,
	'options' => [
		'class' => 'attribute_table'
	],
	'columns' => [
		[
			'attribute' => 'type',
			'value' => function($model) {
				/** @var DynamicAttributeProperty $model */
				return ArrayHelper::getValue(ArrayHelper::getColumn(DynamicAttributeProperty::PROPERTY_TYPES, 'label'), $model->type);
			},
			'visible' => $show_category,
			'options' => [
				'style' => 'width:20%'
			]
		],
		[
			'attribute' => 'name',
			'visible' => false //score widget display group name internally
		],
		[
			'attribute' => 'value',
			'value' => function($model) {
				/** @var DynamicAttributeProperty $model */
				switch ($model->type) {
					case 'boolean':
						return (null === $model->value)?null:($model->value?'Да':'Нет');
					break;
					case 'percent':
						return (null === $model->value)?null:$model->value.'%';
					break;
					case 'score':
						return ScoreWidget::widget([
							'caption' => $model->name,
							'score' => $model->value,
							'readOnly' => true,
							'showEmpty' => false
						]);
					break;
					default:
						return $model->value;
					break;
				}
			},
			'options' => [
				'style' => 'width:50%'
			],
			'format' => 'raw'
		]
	]
]); ?>


