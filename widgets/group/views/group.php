<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Users[]|null $users
 * @var Groups $group
 */

use app\models\groups\Groups;
use app\models\users\Users;
use yii\web\View;
use app\widgets\user\UserWidget;

if (null === $users) $users = $group->relUsers;
?>

<div class="panel">
	<div class="panel-heading">
		<h3 class="panel-title"><?= $group->name; ?></h3>
	</div>
	<div class="panel-body">
		<?php foreach ($users as $user): ?>
			<?= UserWidget::widget([
				'user' => $user,
				'mode' => $group->isLeader($user)?'boss':'user'
			]) ?>
		<?php endforeach; ?>
	</div>
</div>

