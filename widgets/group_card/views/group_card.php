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
$this->registerJs("normalize_widths()", View::POS_END);
$this->registerJs("var Msnry = new Masonry('.grid',{columnWidth: '.grid-sizer', itemSelector: '.panel-card', percentPosition: true, fitWidth: true}); ", View::POS_END);
$this->registerJs("Msnry.layout();", View::POS_LOAD);
?>

<div class="panel panel-card" data-filter='<?= BadgeWidget::widget(['models' => $group->relGroupTypes, 'useBadges' => false, 'attribute' => 'id']) ?>'>
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
					'linkScheme' => [GroupsModule::to(), 'GroupsSearch[type]' => 'id']
				]),
				"badgeOptions" => [
					'class' => "badge badge-info"
				],
				"optionsMap" => RefGroupTypes::colorStyleOptions(),
				"optionsMapAttribute" => 'type',
				'linkScheme' => ['users', 'UsersSearch[groupId]' => $group->id]

			]) ?></h3>
	</div>

	<div class="panel-body">
		<?php foreach ($group->getGroupPositionTypeData() as $key => $positionType): ?>
			<div class="row">
				<div class="col-md-10"><?= BadgeWidget::widget([
						'models' => $positionType->name,
						"badgeOptions" => [
							'style' => $positionType->style
						],
						'linkScheme' => ['users', 'UsersSearch[positionType]' => $positionType->id, 'UsersSearch[groupId]' => $group->id]

					]) ?></div>
				<div class="col-md-2 pad-no">
					<?= BadgeWidget::widget([
						'models' => $positionType->count,
						"badgeOptions" => [
							'style' => $positionType->style,
							'class' => "badge pull-right"
						],
						'linkScheme' => ['users', 'UsersSearch[positionType]' => $positionType->id, 'UsersSearch[groupId]' => $group->id]

					]) ?>
				</div>
			</div>
			<div class="list-divider"></div>
		<?php endforeach; ?>

		<div class="row">
			<div class="col-md-10"><?= BadgeWidget::widget([
					'models' => 'Вакансии',
					"badgeOptions" => [
						'class' => "badge badge-danger pull-left"
					],
					'linkScheme' => [VacancyModule::to('groups'), 'id' => $group->id]
				]) ?></div>
			<div class="col-md-2 pad-no">
				<?= BadgeWidget::widget([
					'models' => count($group->relVacancy),
					"badgeOptions" => [
						'class' => "badge badge-danger pull-right vacancy-count"
					],
					'linkScheme' => [VacancyModule::to('groups'), 'id' => $group->id]
				]) ?>
			</div>
		</div>
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
