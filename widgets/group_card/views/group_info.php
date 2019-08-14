<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Groups $group
 */

use app\modules\groups\models\Groups;
use app\modules\salary\models\references\RefUserPositionTypes;
use app\modules\users\models\references\RefUserRoles;
use app\modules\users\UsersModule;
use app\modules\vacancy\VacancyModule;
use app\widgets\badge\BadgeWidget;
use yii\web\View;

?>
<?= BadgeWidget::widget([
	'models' => "Всего: ".count($group->relUsers),
	"badgeOptions" => [
		'class' => "badge badge-info"
	],
	'linkScheme' => ['users', 'UsersSearch[groupId]' => $group->id]

]) ?>

<?php foreach ($group->getGroupPositionTypeData() as $positionId => $positionCount): ?>
	<?php /** @var RefUserPositionTypes $positionType */
	$positionType = RefUserPositionTypes::findModel($positionId); ?>
	<?= BadgeWidget::widget([
		'models' => "{$positionType->name}: {$positionCount}",
		"badgeOptions" => [
			'style' => "background: {$positionType->color}; color: $positionType->textcolor"
		],
		'linkScheme' => ['users', 'UsersSearch[positionType]' => $positionId, 'UsersSearch[groupId]' => $group->id]

	]) ?>

<?php endforeach; ?>

<?= BadgeWidget::widget([
	'models' => "Вакансии: ".count($group->relVacancy),
	"badgeOptions" => [
		'class' => "badge badge-danger"
	],
	'linkScheme' => [VacancyModule::to('groups'), 'id' => $group->id]
]) ?>

<?= BadgeWidget::widget([
	'models' => static function() use ($group) {
		$result = [];
		foreach ($group->leaders as $leader) {
			$result[] = BadgeWidget::widget([
				'models' => RefUserRoles::getUserRolesInGroup($leader->id, $group->id),
				'attribute' => 'name',
				'useBadges' => true,
				'itemsSeparator' => false,
				"optionsMap" => RefUserRoles::colorStyleOptions(),
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
