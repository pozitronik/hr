<?php
declare(strict_types = 1);

use app\helpers\ArrayHelper;
use app\models\groups\Groups;
use app\models\relations\RelUsersGroups;
use app\widgets\group_select\GroupSelectWidget;

/**
 * @var View $this
 * @var Users $model
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
				'name',
				[
					'value' => function($group) use ($model) {
						/** @var Groups $model */
						$relUsersGroups = RelUsersGroups::find()->where(['user_id' => $model->id, 'group_id' => $group->id])->one();
						return implode(ArrayHelper::getColumn($relUsersGroups->refUserRoles, 'name'),',');
					}
				]

			]

		]); ?>
	</div>
</div>