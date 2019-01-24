<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Groups $group
 * @var Users[] $users
 */

use app\helpers\ArrayHelper;
use app\helpers\Utils;
use app\models\groups\Groups;
use app\models\user\CurrentUser;
use app\models\users\Users;
use app\widgets\badge\BadgeWidget;
use yii\helpers\Html;
use yii\web\View;
use app\widgets\user\UserWidget;


?>

<div class="panel">
	<div class="panel-heading">
		<div class="panel-control">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#tab1-<?= $group->id ?>" data-toggle="tab">Сотрудники</a></li>
				<li><a href="#tab2-<?= $group->id ?>" data-toggle="tab">Описание</a></li>
				<?php if ($group->isLeader(CurrentUser::User())): ?>
					<li>
						<a href="#tab3-<?= $group->id ?>" data-toggle="tab">
							<div class="crown-mark"></div>
							Запрос на сотрудника</a>
					</li>
				<?php endif; ?>
			</ul>
		</div>
		<?= Html::a(Html::img($group->logo, ['class' => 'panel-logo']), ['/admin/groups/update', 'id' => $group->id]); ?>
		<h3 class="panel-title"><?= Html::a(ArrayHelper::getValue($group->relGroupTypes, 'name', 'Нет типа').": ".$group->name, ['/admin/groups/update', 'id' => $group->id]); ?></h3>
	</div>
	<div class="panel-body">
		<div class="tab-content">
			<div class="tab-pane fade in active" id="tab1-<?= $group->id ?>">
				<?php foreach ($users as $user): ?>
					<?= UserWidget::widget(compact('user', 'group')); ?>
				<?php endforeach; ?>
			</div>
			<div class="tab-pane fade" id="tab2-<?= $group->id ?>">
				<?= $group->comment ?>
			</div>
			<?php if ($group->isLeader(CurrentUser::User())): ?>
				<div class="tab-pane fade" id="tab3-<?= $group->id ?>">
					<?= $this->render('_chunks/employee_request'); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
	<div class="panel-footer">
		<!--Сводка-->
		<?php if (0 < ($childCount = $group->childGroupsCount)): ?>
			Ещё <?= Utils::pluralForm($childCount, ['дочерняя группа', 'дочерние группы', 'дочерних групп']); ?>:
			<?= BadgeWidget::widget([
				'data' => $group->relChildGroups,
				'attribute' => 'name',
				'unbadgedCount' => 3,
				'moreBadgeOptions' => ['class' => 'badge'],
				'itemsSeparator' => false,
				'linkScheme' => ['admin/groups/update', 'id' => 'id']
			]); ?>
			<?= Html::a('Визуализация иерархии', ['admin/groups/tree', 'id' => $group->id], ['class' => 'btn btn-xs btn-info pull-right']); ?>
		<?php endif; ?>

	</div>
</div>

