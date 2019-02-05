<?php
declare(strict_types = 1);

use app\helpers\Icons;
use app\modules\dynamic_attributes\models\DynamicAttributes;
use app\modules\dynamic_attributes\models\DynamicAttributeProperty;
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
			'class' => ActionColumn::class,
			'header' => Icons::menu(),
			'dropdown' => true,
			'dropdownButton' => [
				'label' => Icons::menu(),
				'caret' => ''
			],
			'template' => '{update} {delete}',
			'buttons' => [
				'update' => function($url, $model) use ($attribute) {
					/** @var DynamicAttributeProperty $model */

					return Html::tag('li', Html::a(Icons::update().'Изменение', ['admin/attributes/property', 'attribute_id' => $attribute->id, 'property_id' => ArrayHelper::getValue($model, 'id')]));
				},
				'delete' => function($url, $model) use ($attribute) {
					/** @var DynamicAttributeProperty $model */
					return Html::tag('li', Html::a(Icons::delete().'Удаление', ['admin/attributes/property-delete', 'attribute_id' => $attribute->id, 'property_id' => ArrayHelper::getValue($model, 'id')], [
						'title' => 'Удалить запись',
						'data' => [
							'confirm' => 'Вы действительно хотите удалить запись?',
							'method' => 'post'
						]
					]));
				}
			]
		],
		[
			'attribute' => 'name',
			'label' => 'Название',
			'value' => function($model) use ($attribute) {
				return Html::a(ArrayHelper::getValue($model, 'name'), ['admin/attributes/property', 'attribute_id' => $attribute->id, 'property_id' => ArrayHelper::getValue($model, 'id')]);
			},
			'format' => 'raw'
		],
		[
			'attribute' => 'type',
			'label' => 'Тип'
		],
		[
			'attribute' => 'required',
			'label' => 'Обязательное свойство',
			'format' => 'boolean'
		]
	]

]); ?>