<?php
declare(strict_types = 1);

use app\helpers\ArrayHelper;
use app\models\groups\Groups;
use app\models\references\refs\RefUserRoles;
use app\widgets\group_select\GroupSelectWidget;
use app\models\users\Users;
use kartik\select2\Select2;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use kartik\grid\GridView;
use kartik\grid\CheckboxColumn;

/**
 * @var View $this
 * @var Users $model
 */

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
					'options' => [
						'style' => 'min-width:500px'
					],
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
				[
					'format' => 'raw',
					'attribute' => 'name',
					'value' => function($group) {
						/** @var Groups $group */
						return Html::a($group->name, Url::to(['admin/groups/update', 'id' => $group->id]));
					}
				],
				[
					'label' => 'Роли в группе',
					'value' => function($group) use ($model) {
						/** @var Groups $group */
						return Select2::widget([
							'data' => RefUserRoles::mapData(),
							'name' => "UserRoles[$group->id]",
							/** @var Users $model */
							'value' => ArrayHelper::getColumn(RefUserRoles::getUserRolesInGroup($model, $group), 'id'),
							'options' => ['placeholder' => 'Укажите роль в группе'],
							'pluginOptions' => [
								'allowClear' => true,
								'multiple' => true
							]
						]);
					},
					'format' => 'raw'
				]

			]

		]); ?>
	</div>
</div>