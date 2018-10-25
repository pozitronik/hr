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
		<div class="panel-control">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#tab1-<?= $group->id ?>" data-toggle="tab">Пользователи</a></li>
				<li><a href="#tab2-<?= $group->id ?>" data-toggle="tab">Описание</a></li>
			</ul>
		</div>
		<h3 class="panel-title"><?= $group->name; ?></h3>
	</div>
	<div class="panel-body">
		<div class="tab-content">
			<div class="tab-pane fade in active" id="tab1-<?= $group->id ?>">
				<?php foreach ($users as $user): ?>
					<?= UserWidget::widget([
						'user' => $user,
						'mode' => $group->isLeader($user)?'boss':'user'
					]); ?>
				<?php endforeach; ?>
			</div>
			<div class="tab-pane fade" id="tab2-<?= $group->id ?>">
				<?= $group->comment ?>
			</div>
		</div>



	</div>
</div>

