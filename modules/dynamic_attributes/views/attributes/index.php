<?php
declare(strict_types = 1);

/**
 * Шаблон списка атрибутов
 * @var View $this
 * @var UsersSearch $searchModel
 * @var ActiveDataProvider $dataProvider
 */

use app\helpers\IconsHelper;
use pozitronik\helpers\Utils;
use app\modules\dynamic_attributes\DynamicAttributesModule;
use app\modules\dynamic_attributes\models\DynamicAttributes;
use app\modules\dynamic_attributes\widgets\navigation_menu\AttributeNavigationMenuWidget;
use app\modules\users\models\UsersSearch;
use app\widgets\badge\BadgeWidget;
use kartik\grid\DataColumn;
use yii\bootstrap\ButtonGroup;
use yii\data\ActiveDataProvider;
use yii\web\View;
use kartik\grid\GridView;
use yii\bootstrap\Html;

$this->title = 'Атрибуты';
$this->params['breadcrumbs'][] = $this->title;
?>

<?= GridView::widget([
	'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
	'panel' => [
		'heading' => $this->title.(($dataProvider->totalCount > 0)?" (".Utils::pluralForm($dataProvider->totalCount, ['атрибут', 'атрибута', 'атрибутов']).")":" (нет атрибутов)")
	],
	'summary' => ButtonGroup::widget([
		'options' => [
			'class' => 'summary-content'
		],
		'buttons' => [
			DynamicAttributesModule::a('Новый атрибут', 'attributes/create', ['class' => 'btn btn-success']),
			DynamicAttributesModule::a('Поиск', 'attributes/search', ['class' => 'btn btn-info'])
		]
	]),
	'toolbar' => false,
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
			'value' => static function(DynamicAttributes $model) {
				return AttributeNavigationMenuWidget::widget([
					'model' => $model,
					'mode' => AttributeNavigationMenuWidget::MODE_ACTION_COLUMN_MENU
				]);
			},
			'format' => 'raw'
		],
		[
			'attribute' => 'id',
			'options' => [
				'style' => 'width:36px'

			]
		],
		[
			'attribute' => 'name',
			'value' => static function(DynamicAttributes $model) {
				return Html::a($model->name, ['update', 'id' => $model->id]);
			},
			'format' => 'raw'
		],
		'categoryName',
		[
			'attribute' => 'usersCount',
			'header' => IconsHelper::users(),
			'headerOptions' => ['class' => 'text-center'],
			'value' => static function(DynamicAttributes $model) {
				return BadgeWidget::widget([//Нет способа генерировать ссылки со свойствами в BadgeWidget, поэтому оформляем так
					'models' => DynamicAttributesModule::a((string)$model->usersCount, 'attributes/search', [
						'data-method' => 'POST',
						'data-params' => [
							'DynamicAttributesSearchCollection[searchScope][0]' => 0,
							'DynamicAttributesSearchCollection[searchTree]' => 1,
							'DynamicAttributesSearchCollection[searchItems][0][union]' => 0,
							'DynamicAttributesSearchCollection[searchItems][0][attribute]' => $model->id
						]
					])
				]);

			},
			'format' => 'raw'
		]
	]

]) ?>