<?php
declare(strict_types = 1);

use app\models\users\Users;
use app\models\groups\Groups;
use app\widgets\roles_select\RolesSelectWidget;
use yii\data\ActiveDataProvider;
use yii\web\View;
use kartik\grid\GridView;
use kartik\grid\CheckboxColumn;
use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\user_select\UserSelectWidget;

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
				[
					'options' => [
						'style' => 'min-width:500px'
					],
					'content' => UserSelectWidget::widget([
						'model' => $model,
						'attribute' => 'relUsers',
						'notData' => $model->isNewRecord?[]:array_merge($model->relUsers, [$model]),
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
						return RolesSelectWidget::widget([
							'groupId' => $model->id,
							'userId' => $user->id
						]);
					},
					'format' => 'raw'
				]
			]
		]); ?>
	</div>
</div>