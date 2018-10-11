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

?>
<div class="row">
	<div class="col-xs-12">
		<?= GridView::widget([
			'dataProvider' => new ActiveDataProvider([
				'query' => $model->getRelChildGroups()->orderBy('name')
			]),
			'panel' => [
				'heading' => "Дочерние группы"
			],
			'toolbar' => [
				[
					'content' => GroupSelectWidget::widget([
						'model' => $model,
						'attribute' => 'relChildGroups',
						'notData' => array_merge($model->relChildGroups, [$model]),
						'multiple' => true
					])
				]

			],
			'export' => false,
			'resizableColumns' => true,
			'responsive' => true,
			'columns' => [
				[
					'class' => CheckboxColumn::class,
					'width' => '36px',
					'headerOptions' => ['class' => 'kartik-sheet-style'],
					'header' => 'Удалить',
					'name' => $model->classNameShort.'[dropChildGroups]'
				],
				'name'

			]

		]); ?>
	</div>
</div>