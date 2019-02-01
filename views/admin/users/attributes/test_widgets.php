<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Users $user
 *
 */

use app\models\users\Users;
use app\widgets\user_attributes\UserAttributesWidget;
use yii\web\View; ?>

<?php foreach ($user->relDynamicAttributes as $model): ?>
	<?= UserAttributesWidget::widget([
		'user_id' => $user->id,
		'attribute_id' => $model->id,
	]); ?>

<?php endforeach; ?>
