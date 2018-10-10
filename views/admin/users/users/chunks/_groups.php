<?php
declare(strict_types = 1);

use app\widgets\group_select\GroupSelectWidget;

/**
 * Шаблон формы страницы изменения информации пользователя
 *
 * @var View $this
 * @var Users $model
 * @var
 */

use app\models\users\Users;
use yii\data\ActiveDataProvider;
use yii\web\View;
use kartik\grid\GridView;
use kartik\grid\CheckboxColumn;

?>
<div class="row">
	<div class="col-xs-12">
		<?= GridView::widget([
			'dataProvider' => new ActiveDataProvider([
				'query' => $model->getRelGroups()->orderBy('name')
			]),
			'panel' => [
				'heading' => "Группы пользователя"
			],
			'toolbar' => [
				[
					'content' => GroupSelectWidget::widget([
						'model' => $model,
						'attribute' => 'relGroups',
						'notData' => $model->relGroups,
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
					'name' => $model->classNameShort.'[dropGroups]'
				],
				'name'

			]

		]); ?>
	</div>
</div>