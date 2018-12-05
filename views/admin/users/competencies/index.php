<?php
declare(strict_types = 1);

/**
 * Шаблон главной страницы списка всех пользователей
 * @var View $this
 * @var Users $user
 * @var array $data
 **/

use app\models\competencies\Competencies;
use app\widgets\competency\CompetencyWidget;
use yii\web\View;
use kartik\grid\GridView;
use app\models\users\Users;
use yii\data\ActiveDataProvider;
use kartik\select2\Select2;
use kartik\grid\CheckboxColumn;
use yii\helpers\Html;
use yii\helpers\Url;

$provider = new ActiveDataProvider([
	'query' => $user->getRelCompetencies()->orderBy('name')->active()
]);//todo controller
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
							'show_category' => true
						]);
					},
					'format' => 'raw'
				]
			]

		]); ?>
	</div>
</div>