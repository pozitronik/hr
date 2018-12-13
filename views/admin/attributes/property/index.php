<?php
declare(strict_types = 1);

use app\models\dynamic_attributes\DynamicAttributes;
use app\models\dynamic_attributes\DynamicAttributeProperty;
use yii\data\BaseDataProvider;
use yii\web\View;
use kartik\grid\GridView;
use yii\helpers\Html;
use kartik\grid\ActionColumn;
use app\helpers\ArrayHelper;

/**
 * @var View $this
 * @var DynamicAttributes $attribute
 * @var BaseDataProvider $provider
 */

?>


<?= GridView::widget([
	'dataProvider' => $provider,
	'panel' => [
		'type' => GridView::TYPE_DEFAULT,
		'after' => false,
		'heading' => false,
		'footer' => $provider->totalCount > $provider->pagination->pageSize?null:false
	],
	'toolbar' => [
		[
			'content' => Html::a('Добавить свойство', ['property', 'attribute_id' => $attribute->id], ['class' => 'btn btn-success'])
		]
	],
	'export' => false,
	'resizableColumns' => true,
	'responsive' => true,
	'columns' => [
		[
			'attribute' => 'id',
			'label' => 'id'
		],
		[
			'attribute' => 'name',
			'label' => 'Название'
		],
		[
			'attribute' => 'type',
			'label' => 'Тип'
		],
		[
			'attribute' => 'required',
			'label' => 'Обязательное свойство',
			'format' => 'boolean'
		],
		[
			'class' => ActionColumn::class,
			'template' => '{update} {delete}',
			'buttons' => [
				'update' => function($url, $model) use ($attribute) {
					/** @var DynamicAttributeProperty $model */
					return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['admin/attributes/property', 'attribute_id' => $attribute->id, 'property_id' => ArrayHelper::getValue($model, 'id')]);
				}
			]
		]
	]

]); ?>