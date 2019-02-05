<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Users $user
 * @var string $before
 */

use app\models\users\Users;
use app\modules\dynamic_attributes\widgets\user_attribute\UserAttributeWidget;
use yii\web\View; ?>

<div class="panel panel-default">
	<div class="kv-panel-before">
		<?= $before ?>
	</div>

	<?php foreach ($user->relDynamicAttributes as $model): ?>
		<?= UserAttributeWidget::widget([
			'user_id' => $user->id,
			'attribute_id' => $model->id
		]); ?>

	<?php endforeach; ?>
</div>



