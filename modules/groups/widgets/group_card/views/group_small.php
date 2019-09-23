<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Groups $group
 * @var array $options -- 'showChildGroups':bool -- показывать дочерние группы; 'col-md' -- значение для модификатора колонк
 */

use app\helpers\IconsHelper;
use app\helpers\Utils;
use app\modules\groups\GroupsModule;
use app\modules\groups\models\Groups;
use app\modules\groups\models\references\RefGroupTypes;
use app\modules\users\models\references\RefUserRoles;
use app\modules\users\UsersModule;
use app\modules\vacancy\VacancyModule;
use app\widgets\badge\BadgeWidget;
use app\widgets\group_card\GroupCardWidget;
use pozitronik\helpers\ArrayHelper;
use yii\web\View;

$childGroupsCount = count($group->relChildGroups);
switch ($childGroupsCount) {
	case 1:
		$mdValue = 12;
	break;
	case 2:
		$mdValue = 6;
	break;
	case 3:
	default:
		$mdValue = 4;
	break;

}
?>

<div class="panel panel-card-small col-md-<?= ArrayHelper::getValue($options, 'col-md', $mdValue) ?>" data-filter='<?= BadgeWidget::widget(['models' => $group->relGroupTypes, 'useBadges' => false, 'attribute' => 'id']) ?>'>
	<div class="panel-heading">
		<div class="panel-control">
			<?= BadgeWidget::widget([
				'models' => count($group->relUsers),
				"badgeOptions" => [
					'class' => "badge badge-info count"
				],
				'linkScheme' => ['users', 'UsersSearch[groupId]' => $group->id]
			]) ?>
		</div>
		<div class="panel-title">
			<?= BadgeWidget::widget([
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
					'iconify' => true,
					'linkScheme' => [GroupsModule::to(), 'GroupsSearch[type]' => 'id']
				]),
				"badgeOptions" => [
					'class' => "badge badge-info"
				],
				"optionsMap" => RefGroupTypes::colorStyleOptions(),
				"optionsMapAttribute" => 'type',
				'linkScheme' => [GroupsModule::to(['groups/profile', 'id' => $group->id])]

			]) ?>
		</div>
	</div>

	<div class="panel-body">
		<?php foreach ($group->getGroupPositionTypeData() as $key => $positionType): ?>
			<?= BadgeWidget::widget([
				'models' => "{$positionType->name}: {$positionType->count}",
				"badgeOptions" => [
					'style' => $positionType->style
				],
				'linkScheme' => ['users', 'UsersSearch[positionType]' => $positionType->id, 'UsersSearch[groupId]' => $group->id]

			]) ?>
		<?php endforeach; ?>
		<?= BadgeWidget::widget([
			'models' => 'Вакансии: '.count($group->relVacancy),
			"badgeOptions" => [
				'class' => "badge ".((count($group->relVacancy) > 0)?"badge-danger":"badge-unimportant")
			],
			'linkScheme' => [VacancyModule::to('groups'), 'id' => $group->id]
		]) ?>

		<?php if (ArrayHelper::getValue($options, 'showChildGroups', true) && $childGroupsCount > 0): ?>
			<button class="btn btn-xs btn-xxs collapsed pull-right" data-target="#childGroups-<?= $group->id ?>" data-toggle="collapse" aria-expanded="false">
				<?= Utils::pluralForm($childGroupsCount, ['подгруппа', 'подгруппы', 'подгрупп']) ?> <?= IconsHelper::expand() ?>
			</button>
		<?php endif; ?>
	</div>

	<?php if (ArrayHelper::getValue($options, 'showChildGroups', true) && $childGroupsCount > 0): ?>

		<div id="childGroups-<?= $group->id ?>" class="collapse" aria-expanded="false" style="height: 0px;">
			<div class="list-divider"></div>
			<div class="row child-groups">
				<div class="col-md-12">
					<?php foreach ($group->relChildGroups as $childGroup): ?>
						<?= GroupCardWidget::widget(['group' => $childGroup, 'view' => 'group_small', 'options' => ['col-md' => 12]]) ?>
					<?php endforeach; ?>
				</div>
			</div>
		</div>


	<?php endif; ?>

	<div class="panel-footer">
		<?= BadgeWidget::widget([
			'models' => static function() use ($group) {
				$result = [];
				foreach ($group->leaders as $leader) {
					$result[] = BadgeWidget::widget([
						'models' => RefUserRoles::getUserRolesInGroup($leader->id, $group->id),
						'attribute' => 'name',
						'useBadges' => true,
						'itemsSeparator' => false,
						"optionsMap" => static function() {
							return RefUserRoles::colorStyleOptions();
						},
						'prefix' => BadgeWidget::widget([
								'models' => $leader,
								'useBadges' => false,
								'attribute' => 'username',
								'unbadgedCount' => 3,
								'itemsSeparator' => false,
								'linkScheme' => [UsersModule::to(['users/profile']), 'id' => $leader->id]
							]).': ',
						'linkScheme' => [UsersModule::to(), 'UsersSearch[roles]' => 'id']
					]);
				}
				return $result;
			},
			'itemsSeparator' => "<span class='pull-right'>,&nbsp;</span>",
			'badgeOptions' => [
				'class' => "pull-right"
			]
		]) ?>
	</div>
</div>
