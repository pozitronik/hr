<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Groups $group
 * @var Groups[] $childGroups
 * @var array $options -- 'showChildGroups':bool -- показывать дочерние группы
 */

use app\modules\groups\GroupsModule;
use app\modules\groups\models\Groups;
use app\modules\groups\models\references\RefGroupTypes;
use app\modules\groups\widgets\group_leaders\GroupLeadersWidget;
use app\modules\home\HomeModule;
use app\modules\users\models\references\RefUserRoles;
use app\modules\users\UsersModule;
use app\modules\vacancy\VacancyModule;
use app\widgets\badge\BadgeWidget;
use app\modules\groups\widgets\group_card\GroupCardWidget;
use pozitronik\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;

//$this->registerJs("normalize_widths()", View::POS_END);
//$this->registerJs("var Msnry = new Masonry('.grid',{columnWidth: '.grid-sizer', itemSelector: '.panel-card', percentPosition: true, fitWidth: true}); ", View::POS_END);
//$this->registerJs("Msnry.layout();", View::POS_LOAD);
?>

<div class="panel panel-card" data-filter='<?= BadgeWidget::widget(['models' => $group->relGroupTypes, 'useBadges' => false, 'attribute' => 'id']) ?>'>
	<div class="panel-heading">
		<div class="panel-control">
			<?= $this->render('control_block', ['target' => "panel-card-{$group->id}"]) ?>
		</div>
		<h3 class="panel-title"><?= BadgeWidget::widget([
				'models' => $group,
				'attribute' => 'name',
				'prefix' => BadgeWidget::widget([
					'models' => $group->relGroupTypes,
					'useBadges' => true,
					'attribute' => 'name',
					'unbadgedCount' => 3,
					'itemsSeparator' => false,
					"optionsMap" => RefGroupTypes::colorStyleOptions(),
					"badgeOptions" => [
						'class' => 'badge group-type-name'
					],
					'linkScheme' => [GroupsModule::to(), 'GroupsSearch[type]' => 'id'],
					'iconify' => true
				]),
				"badgeOptions" => [
					'class' => "badge badge-info"
				],
				"optionsMap" => RefGroupTypes::colorStyleOptions(),
				"optionsMapAttribute" => 'type',
				'linkScheme' => [GroupsModule::to(['groups/profile', 'id' => $group->id])]

			]) ?></h3>

	</div>

	<div class="panel-body">
		<div class="row">
			<div class="col-md-10"><?= BadgeWidget::widget([
					'models' => 'Сотрудники:',
					"badgeOptions" => [
						'class' => "badge badge-info"
					],
					'linkScheme' => [HomeModule::to(['home/users', 'UsersSearch[groupId]' => $group->id])]
				]) ?></div>
			<div class="col-md-2 pad-no">
				<?= BadgeWidget::widget([
					'models' => $group->getRelUsers()->countFromCache(),
					"badgeOptions" => [
						'class' => "badge badge-info pull-right"
					],
					'linkScheme' => [HomeModule::to(['home/users', 'UsersSearch[groupId]' => $group->id])]
				]) ?>
			</div>
		</div>
		<div class="list-divider"></div>
		<?php foreach ($group->getGroupPositionTypeData() as $key => $positionType): ?>
			<div class="row">
				<div class="col-md-10"><?= BadgeWidget::widget([
						'models' => $positionType->name,
						"badgeOptions" => [
							'style' => $positionType->style
						],
						'linkScheme' => [HomeModule::to(['home/users', 'UsersSearch[positionType]' => $positionType->id, 'UsersSearch[groupId]' => $group->id])]

					]) ?></div>
				<div class="col-md-2 pad-no">
					<?= BadgeWidget::widget([
						'models' => $positionType->count,
						"badgeOptions" => [
							'style' => $positionType->style,
							'class' => "badge pull-right"
						],
						'linkScheme' => [HomeModule::to(['home/users', 'UsersSearch[positionType]' => $positionType->id, 'UsersSearch[groupId]' => $group->id])]

					]) ?>
				</div>
			</div>
			<div class="list-divider"></div>
		<?php endforeach; ?>

		<div class="row">
			<div class="col-md-2"><?= BadgeWidget::widget([
					'models' => 'Вакансии: '.Html::tag('span', $group->getRelVacancy()->countFromCache(), ['class' => 'vacancy-count']),
					"badgeOptions" => [
						'class' => "badge pull-left ".(($group->getRelVacancy()->countFromCache() > 0)?"badge-danger":"badge-unimportant")
					],
					'linkScheme' => [VacancyModule::to('groups'), 'id' => $group->id]
				]) ?></div>
			<div class="col-md-10 pad-no">
				<?php foreach ($group->getGroupVacancyStatusData() as $key => $vacancyStatus): ?>
					<?= BadgeWidget::widget([
						'models' => (0 === $vacancyStatus->count)?null:"{$vacancyStatus->name}: {$vacancyStatus->count}",
						"badgeOptions" => [
							'style' => $vacancyStatus->style,
							'class' => "badge pull-right"
						],
						'linkScheme' => [VacancyModule::to('groups'), 'id' => $group->id]
					]) ?>
				<?php endforeach; ?>


			</div>
		</div>

		<?php if (ArrayHelper::getValue($options, 'showChildGroups', true) && $group->getChildGroupsCount() > 0): ?>
			<div class="list-divider"></div>
			<div class="row child-groups">
				<div class="col-md-12">
					<?php foreach ($childGroups as $childGroup): ?>
						<?= $this->render('group_small', ['group' => $childGroup, 'options' => ['showChildGroups' => true]]) ?>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>
	</div>


	<div class="panel-footer">
		<?= GroupLeadersWidget::widget(['group' => $group]) ?>
	</div>
</div>
