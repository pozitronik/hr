<?php
declare(strict_types = 1);

use app\models\users\Users;
use app\models\groups\Groups;
use app\widgets\badge\BadgeWidget;
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
$provider = new ActiveDataProvider(['query' => $model->getRelUsersHierarchy()]);//todo controller

?>
<div class="row">
	<div class="col-xs-12">
		<?= GridView::widget([
			'dataProvider' => $provider,
			'panel' => [
				'type' => GridView::TYPE_DEFAULT,
				'after' => false,
				'heading' => false,
				'footer' => $provider->totalCount > $provider->pagination->pageSize?null:false,
				'before' => false
			],
			'toolbar' => false,
			'export' => false,
			'resizableColumns' => true,
			'responsive' => true,
			'columns' => [
				[
					'format' => 'raw',
					'attribute' => 'groupName',
					'label' => 'Группы',
					'value' => function($model) {
						/** @var Users $model */
						return BadgeWidget::widget([
							'data' => $model->relGroups,
							'unbadgedCount' => 5,
							'badgeClass' => 'pull-right',
							'attribute' => 'name',
							'linkScheme' => ['admin/groups/update', 'id' => 'id'],
						]);
					}
				],
				[
					'format' => 'raw',
					'attribute' => 'username',
					'value' => function($user) {
						/** @var Users $user */
						return Html::a($user->username, ['admin/users/update', 'id' => $user->id]);
					}
				]
			]
		]); ?>
	</div>
</div>