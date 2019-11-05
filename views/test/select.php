<?php
declare(strict_types = 1);

/**
 * @var View $this ;
 * @var ActiveDataProvider $dataProvider
 * @var Reference|false $class
 * @var RefUserPositions $searchModel
 */

use app\modules\references\models\Reference;
use app\modules\references\ReferencesModule;
use app\modules\references\widgets\reference_select\ReferenceSelectWidget;
use app\modules\salary\models\references\RefUserPositions;
use app\modules\salary\models\references\RefUserPositionTypes;
use app\widgets\badge\BadgeWidget;
use kartik\grid\DataColumn;
use kartik\grid\GridView;
use kartik\select2\Select2;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;

?>
<?= GridView::widget([
	'filterModel' => $searchModel,
	'dataProvider' => $dataProvider,
	'columns' => [
		[
			'attribute' => 'id',
			'options' => [
				'style' => 'width:36px;'
			]
		],
		[
			'attribute' => 'name',
			'value' => static function(RefUserPositions $model) {
				return $model->deleted?Html::tag('span', "Удалено:", [
						'class' => 'label label-danger'
					]).$model->name:BadgeWidget::widget([
					'models' => $model,
					'attribute' => 'name',
					'linkScheme' => [ReferencesModule::to(['references/update']), 'id' => 'id', 'class' => $model->formName()],
					'itemsSeparator' => false,
					"optionsMap" => RefUserPositions::colorStyleOptions()
				]);
			},
			'format' => 'raw'
		],
//		[
//			'class' => DataColumn::class,
//			'label' => 'Тип должности select',
//			'attribute' => 'typesId',
//			'format' => 'raw',
//			'filterType' => GridView::FILTER_SELECT2,
//			'filterInputOptions' => ['placeholder' => 'Фильтр по типу'],
//			'filter' => RefUserPositionTypes::mapData(),
//			'filterWidgetOptions' => [
//				'pluginOptions' => [
//					'allowClear' => true, 'multiple' => true
//				]
//			],
//			'value' => static function(RefUserPositions $model) {
//				return BadgeWidget::widget([
//					'models' => $model->relRefUserPositionTypes,
//					'attribute' => 'name',
//					'unbadgedCount' => 10,
//					'itemsSeparator' => false,
//					'linkScheme' => [ReferencesModule::to(['references/update']), 'id' => 'id', 'class' => 'RefUserPositionTypes'],
//					"optionsMap" => RefUserPositionTypes::colorStyleOptions()
//				]);
//			}
//		],
		[
			'class' => DataColumn::class,
			'label' => 'Тип должности',
			'attribute' => 'typesId',
			'format' => 'raw',
			'filterType' => ReferenceSelectWidget::class,
			'filter' => RefUserPositionTypes::mapData(),
			'filterInputOptions' => ['placeholder' => 'Фильтр по типу'],
			'filterWidgetOptions' => [
				'referenceClass' => RefUserPositionTypes::class,
				'pluginOptions' => [
					'allowClear' => true, 'multiple' => true//todo #issue 49
				]
			],
			'value' => static function(RefUserPositions $model) {
				return BadgeWidget::widget([
					'models' => $model->relRefUserPositionTypes,
					'attribute' => 'name',
					'unbadgedCount' => 10,
					'itemsSeparator' => false,
					'linkScheme' => [ReferencesModule::to(['references/update']), 'id' => 'id', 'class' => 'RefUserPositionTypes'],
					"optionsMap" => RefUserPositionTypes::colorStyleOptions()
				]);
			}
		]
	]
]) ?>