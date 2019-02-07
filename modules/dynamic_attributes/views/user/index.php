<?php /** @noinspection MissedFieldInspection */
declare(strict_types = 1);

/**
 * @var View $this
 * @var Users $user
 * @var Model $searchModel
 * @var ActiveDataProvider $dataProvider
 */

use app\helpers\ArrayHelper;
use app\models\references\refs\RefAttributesTypes;
use app\models\relations\RelUsersAttributes;
use app\modules\dynamic_attributes\models\DynamicAttributes;
use app\models\users\Users;
use app\modules\dynamic_attributes\widgets\user_attribute\UserAttributeWidget;
use kartik\select2\Select2;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\web\View;
use kartik\grid\GridView;

?>

<div class="panel panel-attribute">
	<div class="panel-body">
		<?= GridView::widget([
			'dataProvider' => $dataProvider,
			'filterModel' => $searchModel,
			'showFooter' => false,
			'showPageSummary' => true,
			'panel' => [
				'type' => GridView::TYPE_DEFAULT,
				'after' => false,
				'before' => Select2::widget([
					'model' => $user,
					'attribute' => 'relDynamicAttributes',
					'name' => 'attribute_id',
					'data' => ArrayHelper::map($user->isNewRecord?DynamicAttributes::find()->active()->all():DynamicAttributes::find()->active()->where(['not in', 'id', ArrayHelper::getColumn($user->relDynamicAttributes, 'id')])->all(), 'id', 'name'),
					'options' => [
						'multiple' => true,
						'placeholder' => 'Добавить атрибут'
					]
				]),
				'heading' => false,
				'footer' => false
			],
			'toolbar' => false,
			'export' => false,
			'resizableColumns' => false,
			'responsive' => true,
			'columns' => [
				[
					'attribute' => 'type',
					'filterType' => GridView::FILTER_SELECT2,//todo создаём виджет AttributesSelect, который будет наследоваться от Select2, но поддерживать выбор моделей атрибутов
					'filter' => RefAttributesTypes::mapData(),
					'filterInputOptions' => ['placeholder' => 'Выберите типы атрибутов'],
					'filterWidgetOptions' => ['pluginOptions' => ['allowClear' => true, 'multiple' => true]],
					'label' => 'Сортировать по типу атрибута',
					'value' => function($model) use ($user) {
						/** @var RelUsersAttributes $model */
						return UserAttributeWidget::widget([
							'user_id' => $user->id,
							'attribute_id' => $model->attribute_id
						]);
					},
					'format' => 'raw'

				]
			]

		]); ?>

	</div>

</div>



