<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var array $structure
 * @var DataProviderInterface $widgetDataProvider
 */

use app\helpers\ArrayHelper;
use app\models\competencies\CompetencyField;
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
		'class' => 'competency_table'
	],
	'columns' => [
		[
			'attribute' => 'type',
			'value' => function($model) {
				/** @var CompetencyField $model */
				return ArrayHelper::getValue(ArrayHelper::getColumn(CompetencyField::FIELD_TYPES, 'label'), $model->type);
			}
		],
		'name',
		[
			'attribute' => 'value',
			'value' => function($model) {
				/** @var CompetencyField $model */
				switch ($model->type) {
					case 'boolean':
						return $model->value?'Да':'Нет';
					break;
					case 'percent':
						return (null === $model->value)?null:$model->value.'%';
					break;
					default:
						return $model->value;
					break;
				}
			},
			'format' => 'raw'
		]
	]
]); ?>


