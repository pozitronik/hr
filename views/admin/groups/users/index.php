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
				'query' => $model->getRelUsers()->active()
			]),
			'panel' => [
				'type' => GridView::TYPE_DEFAULT,
				'after' => false,
				'heading' => false,
				'footer' => false,
				'before' => UserSelectWidget::widget([
					'model' => $model,
					'attribute' => 'relUsers',
					'notData' => $model->relUsers,
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
					'name' => $model->classNameShort.'[dropUsers]'
				],
				[
					'format' => 'raw',
					'attribute' => 'username',
					'value' => function($user) {
						/** @var Users $user */
						return Html::a($user->username, ['admin/users/update', 'id' => $user->id]);
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