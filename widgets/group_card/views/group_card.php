<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Groups $group
 */

use app\modules\groups\models\Groups;
use app\modules\groups\models\references\RefGroupTypes;
use app\modules\salary\models\references\RefUserPositionTypes;
use app\modules\users\models\references\RefUserRoles;
use app\modules\users\UsersModule;
use app\modules\vacancy\VacancyModule;
use app\widgets\badge\BadgeWidget;
use yii\web\View;

?>

<div class="panel panel-card">
	<div class="panel-heading">
		<div class="panel-control">
			<?= BadgeWidget::widget([
				'models' => count($group->relUsers),
				"badgeOptions" => [
					'class' => "badge badge-info"
				],
				'linkScheme' => ['users', 'UsersSearch[groupId]' => $group->id]

			]) ?>
		</div>
		<h3 class="panel-title"><?= BadgeWidget::widget([
				'models' => $group,
				'attribute' => 'name',
				"badgeOptions" => [
					'class' => "badge badge-info"
				],
				"optionsMap" => static function() {
					return RefGroupTypes::colorStyleOptions();
				},
				"optionsMapAttribute" => 'type',
				'linkScheme' => ['users', 'UsersSearch[groupId]' => $group->id]

			]) ?></h3>
	</div>

	<div class="panel-body">
		<?php foreach ($group->getGroupPositionTypeData() as $positionId => $positionCount): ?>
			<?php /** @var RefUserPositionTypes $positionType */
			$positionType = RefUserPositionTypes::findModel($positionId); ?>
			<div class="row">
				<div class="col-md-10"><?= BadgeWidget::widget([
						'models' => $positionType->name,
						"badgeOptions" => [
							'style' => "float:left; background: {$positionType->color}; color: $positionType->textcolor"
						],
						'linkScheme' => ['users', 'UsersSearch[positionType]' => $positionId, 'UsersSearch[groupId]' => $group->id]

					]) ?></div>
				<div class="col-md-2 pad-no">
					<?= BadgeWidget::widget([
						'models' => $positionCount,
						"badgeOptions" => [
							'style' => "float:right; background: {$positionType->color}; color: $positionType->textcolor"
						],
						'linkScheme' => ['users', 'UsersSearch[positionType]' => $positionId, 'UsersSearch[groupId]' => $group->id]

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
						'class' => "badge badge-danger pull-right"
					],
					'linkScheme' => [VacancyModule::to('groups'), 'id' => $group->id]
				]) ?>
			</div>
		</div>
	</div>


	<div class="panel-footer">
		<?= BadgeWidget::widget([
			'models' => function() use ($group) {
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
							]).': '
					]);
				}
				return $result;
			},
			'itemsSeparator' => "<span class='pull-right'>,&nbsp;</span>",
			'badgeOptions' => [
				'class' => "pull-right"
			]
		]); ?>
	</div>
</div>
