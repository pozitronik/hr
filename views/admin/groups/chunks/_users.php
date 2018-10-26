<?php
declare(strict_types = 1);

use app\helpers\ArrayHelper;
use app\models\references\refs\RefUserRoles;
use app\models\users\Users;
use app\models\groups\Groups;
use yii\data\ActiveDataProvider;
use yii\web\View;
use kartik\grid\GridView;
use kartik\grid\CheckboxColumn;
use kartik\select2\Select2;

/**
 * @var View $this
 * @var Groups $model
 */

?>
<div class="row">
	<div class="col-xs-12">
		<?= GridView::widget([
			'dataProvider' => new ActiveDataProvider([
				'query' => $model->getRelUsers()
			]),
			'panel' => [
				'heading' => "Пользователи"
			],
			'toolbar' => [
//				[
//					'options' => [
//						'style' => 'min-width:500px'
//					],
//					'content' => UsersSelectWidget::widget([
//						'model' => $model,
//						'attribute' => 'relChildGroups',
//						'notData' => $model->isNewRecord?[]:array_merge($model->relChildGroups, [$model]),
//						'multiple' => true
//					])
//				]

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
					'name' => $model->classNameShort.'[dropUsers]'
				],
				'username',
				[
					'label' => 'Роли в группе',
					'value' => function($user) use ($model) {
						/** @var Groups $model */
						return Select2::widget([
							'data' => RefUserRoles::mapData(),
							'name' => "UserRoles[$user->id]",
							/** @var Users $model */
							'value' => ArrayHelper::getColumn(RefUserRoles::getUserRolesInGroup($user, $model), 'id'),
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