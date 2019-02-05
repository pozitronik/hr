<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Users $user
 */

use app\helpers\ArrayHelper;
use app\modules\dynamic_attributes\models\DynamicAttributes;
use app\models\users\Users;
use app\modules\dynamic_attributes\widgets\user_attribute\UserAttributeWidget;
use kartik\select2\Select2;
use yii\web\View; ?>

<div class="panel panel-attribute">
	<div class="kv-panel-before">
		<?= Select2::widget([
			'model' => $user,
			'attribute' => 'relDynamicAttributes',
			'name' => 'attribute_id',
			'data' => ArrayHelper::map($user->isNewRecord?DynamicAttributes::find()->active()->all():DynamicAttributes::find()->active()->where(['not in', 'id', ArrayHelper::getColumn($user->relDynamicAttributes, 'id')])->all(), 'id', 'name'),
			'options' => [
				'multiple' => true,
				'placeholder' => 'Добавить атрибут'
			]
		]) ?>
	</div>

	<?php foreach ($user->relDynamicAttributes as $model): ?>
		<?= UserAttributeWidget::widget([
			'user_id' => $user->id,
			'attribute_id' => $model->id
		]); ?>

	<?php endforeach; ?>
</div>



