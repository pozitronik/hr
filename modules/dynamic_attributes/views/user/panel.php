<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Users $user
 */

use pozitronik\helpers\ArrayHelper;
use app\models\relations\RelUsersAttributes;
use app\modules\dynamic_attributes\models\DynamicAttributes;
use app\modules\users\models\Users;
use app\modules\dynamic_attributes\widgets\user_attribute\UserAttributeWidget;
use kartik\select2\Select2;
use yii\web\View;

?>

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

	<?php foreach (RelUsersAttributes::getUserAttributes($user->id) as $model): ?>
		<?= UserAttributeWidget::widget([
			'user_id' => $user->id,
			'attribute_id' => $model->attribute_id
		]) ?>

	<?php endforeach; ?>
</div>



