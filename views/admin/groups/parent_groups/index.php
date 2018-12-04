<?php
declare(strict_types = 1);

use app\models\groups\Groups;
use app\widgets\group_select\GroupSelectWidget;

/**
 *
 * @var View $this
 * @var Groups $model
 */

use yii\data\ActiveDataProvider;
use yii\web\View;
use kartik\grid\GridView;
use kartik\grid\CheckboxColumn;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\ActionColumn;

?>
<div class="row">
	<div class="col-xs-12">
		<?= GridView::widget([
			'dataProvider' => new ActiveDataProvider([
				'query' => $model->getRelParentGroups()->orderBy('name')->active()
			]),
			'panel' => [
				'type' => GridView::TYPE_DEFAULT,
				'after' => false,
				'heading' => false,
				'footer' => false,
				'before' => GroupSelectWidget::widget([
					'model' => $model,
					'attribute' => 'relParentGroups',
					'notData' => $model->isNewRecord?[]:array_merge($model->relParentGroups, [$model]),
					'multiple' => true
				])
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
					'name' => $model->classNameShort.'[dropParentGroups]'
				],
				[
					'format' => 'raw',
					'attribute' => 'name',
					'value' => function($group) {
						/** @var Groups $group */
						return Html::a($group->name, Url::to(['admin/groups/update', 'id' => $group->id]));
					}
				],
				[
					'class' => ActionColumn::class,
					'template' => '{tree}',
					'buttons' => [
						'tree' => function($url, $model) {
							return Html::a('Граф', $url, ['class' => 'btn btn-xs btn-info']);
						}
					]
				]
			]

		]); ?>
	</div>
</div>