<?php
declare(strict_types = 1);

use app\helpers\ArrayHelper;
use app\models\references\refs\RefUserRoles;
use app\models\users\Users;
use app\models\groups\Groups;
use kartik\spinner\Spinner;
use yii\data\ActiveDataProvider;
use yii\web\View;
use kartik\grid\GridView;
use kartik\grid\CheckboxColumn;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;

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
				[
					'format' => 'raw',
					'attribute' => 'name',
					'value' => function($user) {
						/** @var Users $user */
						return Html::a($user->username, Url::to(['admin/users/update', 'id' => $user->id]));
					}
				],
				[
					'label' => 'Роли в группе',
					'value' => function($user) use ($model) {
						/** @var Groups $model */
						return Select2::widget([
							'data' => RefUserRoles::mapData(),
							'name' => "UserRoles[$user->id]",
							/** @var Users $model */
							'value' => ArrayHelper::getColumn(RefUserRoles::getUserRolesInGroup($user->id, $model->id), 'id'),
							'options' => [
								'placeholder' => 'Укажите роль в группе'
							],
							'pluginOptions' => [
								'allowClear' => true,
								'multiple' => true
							],
							'pluginEvents' => [
								"change.select2" => "function(e) {
									jQuery('#{$user->id}-roles-progress').show();
									jQuery.ajax({
  										url: '\/ajax\/set-user-roles-in-group',
  										data: {
  											userid: $user->id,
  											groupid: $model->id,
  											roles: jQuery(e.target).val()
										},
  										method: 'POST'
									}).done(function(data) {
									  jQuery('#{$user->id}-roles-progress').hide();
									});
								
								 }"
							],
							'addon' => [
								'append' => [
									'content' => Spinner::widget(['preset' => 'small', 'align' => 'right', 'hidden' => true, 'id' => "{$user->id}-roles-progress"]),
									'asButton' => false
								]
							]

						]);
					},
					'format' => 'raw'
				]
			]
		]); ?>
	</div>
</div>