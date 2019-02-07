<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Users $user
 */

use app\helpers\ArrayHelper;
use app\modules\dynamic_attributes\models\DynamicAttributes;
use app\models\users\Users;
use app\modules\dynamic_attributes\models\user_attributes\UserAttributesSearch;
use app\modules\dynamic_attributes\widgets\user_attribute\UserAttributeWidget;
use kartik\grid\GridView;
use kartik\select2\Select2;
use yii\web\View;

$searchModel = new UserAttributesSearch(['user_id' => $user->id]);



?>

<div class="panel panel-attribute">
	<div class="panel-body">
		<?= GridView::widget([
			'dataProvider' => $searchModel->search([]),
			'filterModel' => $searchModel,
			'showFooter' => true,
			'showPageSummary' => true,
//			'panel' => [
//				'type' => GridView::TYPE_DEFAULT,
//				'after' => false,
//				'before' => Select2::widget([
//					'model' => $user,
//					'attribute' => 'relDynamicAttributes',
//					'name' => 'attribute_id',
//					'data' => ArrayHelper::map($user->isNewRecord?DynamicAttributes::find()->active()->all():DynamicAttributes::find()->active()->where(['not in', 'id', ArrayHelper::getColumn($user->relDynamicAttributes, 'id')])->all(), 'id', 'name'),
//					'options' => [
//						'multiple' => true,
//						'placeholder' => 'Добавить атрибут'
//					]
//				]),
//				'heading' => false,
//				'footer' => false
//			],
//			'toolbar' => false,
//			'export' => false,
//			'resizableColumns' => true,
//			'responsive' => true,
			'columns' => [
				'user_id',
				'attribute_id',
				[
					'attribute' => 'type',
					'value' => function($model) use ($user) {
						/** @var DynamicAttributes $model */
						return UserAttributeWidget::widget([
							'user_id' => $user->id,
							'attribute_id' => $model->attribute_id,
						]);
					},
					'format' => 'raw'
				]
			]

		]); ?>

	</div>

</div>



