<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Groups $group
 * @var Users[] $users
 */

use pozitronik\helpers\ArrayHelper;
use app\helpers\Utils;
use app\modules\groups\GroupsModule;
use app\modules\groups\models\Groups;
use app\models\user\CurrentUser;
use app\modules\users\models\Users;
use app\widgets\badge\BadgeWidget;
use yii\helpers\Html;
use yii\web\View;
use app\modules\users\widgets\user\UserWidget;

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
		<?= Groups::a(Html::img($group->logo, ['class' => 'panel-logo']), ['groups/profile', 'id' => $group->id]) ?>
		<h3 class="panel-title"><?= Groups::a(ArrayHelper::getValue($group->relGroupTypes, 'name', 'Нет типа').": ".$group->name, ['groups/profile', 'id' => $group->id]) ?></h3>
	</div>
	<div class="panel-body">
		<div class="tab-content">
			<div class="tab-pane fade in active" id="tab1-<?= $group->id ?>">
				<?php foreach ($users as $user): ?>
					<?= UserWidget::widget(compact('user', 'group')) ?>
				<?php endforeach; ?>
			</div>
			<div class="tab-pane fade" id="tab2-<?= $group->id ?>">
				<?= $group->comment ?>
			</div>
			<?php if ($group->isLeader(CurrentUser::User())): ?>
				<div class="tab-pane fade" id="tab3-<?= $group->id ?>">
					<?= $this->render('_chunks/employee_request') ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
	<div class="panel-footer">
		<!--Сводка-->
		<?php if (0 < ($childCount = $group->childGroupsCount)): ?>
			Ещё <?= Utils::pluralForm($childCount, ['дочерняя группа', 'дочерние группы', 'дочерних групп']) ?>:
			<?= BadgeWidget::widget([
				'data' => $group->relChildGroups,
				'attribute' => 'name',
				'unbadgedCount' => 3,
				'moreBadgeOptions' => ['class' => 'badge'],
				'itemsSeparator' => false,
				'linkScheme' => [GroupsModule::to('groups/profile'), 'id' => 'id']
			]) ?>
			<?= Groups::a('Визуализация иерархии', ['groups/tree', 'id' => $group->id], ['class' => 'btn btn-xs btn-info pull-right']) ?>
		<?php endif; ?>

	</div>
</div>

