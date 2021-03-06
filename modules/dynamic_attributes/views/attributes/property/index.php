<?php
declare(strict_types = 1);

use app\components\pozitronik\navigationwidget\BaseNavigationMenuWidget;
use app\models\core\IconsHelper;
use app\modules\dynamic_attributes\models\DynamicAttributes;
use app\modules\dynamic_attributes\models\DynamicAttributeProperty;
use app\modules\dynamic_attributes\widgets\navigation_menu\AttributePropertyNavigationMenuWidget;
use kartik\grid\DataColumn;
use yii\data\BaseDataProvider;
use yii\web\View;
use kartik\grid\GridView;
use yii\helpers\Html;
use app\components\pozitronik\helpers\ArrayHelper;

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
			'class' => DataColumn::class,
			'filter' => false,
			'header' => IconsHelper::menu(),
			'mergeHeader' => true,
			'headerOptions' => [
				'class' => 'skip-export kv-align-center kv-align-middle'
			],
			'contentOptions' => [
				'style' => 'width:50px',
				'class' => 'skip-export kv-align-center kv-align-middle'
			],
			'value' => static function(DynamicAttributeProperty $model) use ($attribute) {
				return AttributePropertyNavigationMenuWidget::widget([
					'model' => $model,
					'attribute' => $attribute,
					'mode' => BaseNavigationMenuWidget::MODE_ACTION_COLUMN_MENU
				]);
			},
			'format' => 'raw'
		],
		[
			'attribute' => 'name',
			'label' => 'Название',
			'value' => static function(DynamicAttributeProperty $model) use ($attribute) {
				return Html::a(ArrayHelper::getValue($model, 'name'), ['property', 'attribute_id' => $attribute->id, 'property_id' => $model->id]);
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

]) ?>