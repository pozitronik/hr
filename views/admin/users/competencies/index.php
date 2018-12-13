<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Users $user
 * @var array $data
 * @var BaseDataProvider $provider
 **/

use app\models\competencies\Competencies;
use app\widgets\competency\CompetencyWidget;
use yii\data\BaseDataProvider;
use yii\grid\ActionColumn;
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
					'attribute' => 'relCompetencies',
					'name' => 'competency_id',
					'data' => $data,
					'options' => [
						'multiple' => true,
						'placeholder' => 'Добавить компетенцию'
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
					'class' => CheckboxColumn::class,
					'width' => '36px',
					'headerOptions' => ['class' => 'kartik-sheet-style'],
					'header' => 'Удалить',
					'name' => $user->formName().'[dropCompetencies]'
				],
				[
					'attribute' => 'name',
					'value' => function($model) use ($user) {
						/** @var Competencies $model */
						return Html::a($model->name, Url::to(['admin/users/competencies', 'user_id' => $user->id, 'competency_id' => $model->id]));
					},
					'format' => 'raw'
				],
				'categoryName',
				[
					'label' => 'Данные',
					'value' => function($model) use ($user) {
						/** @var Competencies $model */
						return CompetencyWidget::widget([
							'user_id' => $user->id,
							'competency_id' => $model->id,
//							'show_category' => true
						]);
					},
					'format' => 'raw'
				],
				[
					'class' => ActionColumn::class,
					'template' => '{graph}',
					'buttons' => [
						'graph' => function($url, $model) use ($user) {
							return Html::a(
								'<span class="glyphicon glyphicon-eye-open"></span>',
								['competency-graph', 'user_id' => $user->id, 'competency_id' => $model->id]
							);
						},
					]
				]
			]

		]); ?>
	</div>
</div>