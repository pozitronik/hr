<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Users $user
 * @var array $data
 * @var BaseDataProvider $provider
 **/

use app\helpers\Icons;
use app\models\dynamic_attributes\DynamicAttributes;
use app\widgets\user_attributes\UserAttributesWidget;
use yii\data\BaseDataProvider;
use kartik\grid\ActionColumn;
use yii\web\View;
use kartik\grid\GridView;
use app\models\users\Users;
use kartik\select2\Select2;
use kartik\grid\CheckboxColumn;
use yii\helpers\Html;
use yii\helpers\Url;

?>

<div class="row">
	<div class="col-xs-12">
		<?= GridView::widget([
			'dataProvider' => $provider,
			'showFooter' => false,
			'showPageSummary' => false,
			'summary' => '',
			'panel' => [
				'type' => GridView::TYPE_DEFAULT,
				'after' => false,
				'before' => Select2::widget([
					'model' => $user,
					'attribute' => 'relDynamicAttributes',
					'name' => 'attribute_id',
					'data' => $data,
					'options' => [
						'multiple' => true,
						'placeholder' => 'Добавить атрибут'
					]
				]),
				'heading' => false,
				'footer' => $provider->totalCount > $provider->pagination->pageSize?null:false
			],
			'toolbar' => false,
			'export' => false,
			'resizableColumns' => true,
			'responsive' => true,
			'columns' => [
				[
					'header' => Icons::menu(),
					'dropdown' => true,
					'dropdownButton' => [
						'label' => Icons::menu(),
						'caret' => ''
					],
					'class' => ActionColumn::class,
					'template' => '{update}{attribute-graph}{clear}',
					'buttons' => [
						'update' => function($url, $model) use ($user) {
							return Html::tag('li', Html::a(Icons::attributes().'Открыть для изменения', Url::to(['admin/users/attributes', 'user_id' => $user->id, 'attribute_id' => $model->id])));
						},
						'attribute-graph' => function($url, $model) use ($user) {
							/** @var DynamicAttributes $model */
							return $model->hasIntegerProperties?Html::tag('li', Html::a(Icons::chart().'Диаграмма', ['attribute-graph', 'user_id' => $user->id, 'attribute_id' => $model->id])):false;
						},
						'clear' => function($url, $model) use ($user) {
							return Html::tag('li', Html::a(Icons::clear().'Сбросить все значения', Url::to(['admin/users/attributes-clear', 'user_id' => $user->id, 'attribute_id' => $model->id])));
						}
					]
				],
				[
					'value' => function($model) use ($user) {
						/** @var DynamicAttributes $model */
						return UserAttributesWidget::widget([
							'user_id' => $user->id,
							'attribute_id' => $model->id,
						]);
					},
					'format' => 'raw'
				],

//				[
//					'attribute' => 'name',
//					'value' => function($model) use ($user) {
//						/** @var DynamicAttributes $model */
//						return Html::a($model->name, Url::to(['admin/users/attributes', 'user_id' => $user->id, 'attribute_id' => $model->id]));
//					},
//					'format' => 'raw'
//				],
//				[
//					'label' => 'Данные',
//					'value' => function($model) use ($user) {
//						/** @var DynamicAttributes $model */
//						return UserAttributesWidget::widget([
//							'user_id' => $user->id,
//							'attribute_id' => $model->id,
//						]);
//					},
//					'format' => 'raw'
//				],
//				[
//					'class' => CheckboxColumn::class,
//					'headerOptions' => ['class' => 'kartik-sheet-style'],
//					'header' => Icons::trash(),
//					'name' => $user->formName().'[dropUsersAttributes]'
//				]
			]

		]); ?>
	</div>
</div>