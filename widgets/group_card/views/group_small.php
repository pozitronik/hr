<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Groups $group
 */

use app\modules\groups\GroupsModule;
use app\modules\groups\models\Groups;
use app\modules\groups\models\references\RefGroupTypes;
use app\modules\users\models\references\RefUserRoles;
use app\modules\users\UsersModule;
use app\modules\vacancy\VacancyModule;
use app\widgets\badge\BadgeWidget;
use yii\web\View;

?>

<div class="panel panel-card-small col-md-3" data-filter='<?= BadgeWidget::widget(['models' => $group->relGroupTypes, 'useBadges' => false, 'attribute' => 'id']) ?>'>
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
	</div>


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
