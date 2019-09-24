<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Groups $group
 * @var array $options
 * todo: схема подстановки порядка и отображения данных
 * todo: фиксы схем
 */

use app\modules\groups\models\Groups;
use app\modules\users\models\references\RefUserRoles;
use app\modules\users\UsersModule;
use app\modules\vacancy\VacancyModule;
use app\widgets\badge\BadgeWidget;
use pozitronik\helpers\ArrayHelper;
use yii\web\View;

?>
<?= BadgeWidget::widget([
	'models' => "Всего: ".$group->getRelUsers()->active()->countFromCache(),
	"badgeOptions" => [
		'class' => "badge badge-info pull-left"
	],
	'linkScheme' => ['users', 'UsersSearch[groupId]' => $group->id]

]) ?>
<?php foreach ($group->getGroupPositionTypeData() as $key => $positionType): ?>
	<?= BadgeWidget::widget([
		'models' => (0 === $positionType->count)?null:"{$positionType->name}: {$positionType->count}",
		"badgeOptions" => [
			'style' => $positionType->style,
			'class' => 'badge pull-left'
		],
		'linkScheme' => ['users', 'UsersSearch[positionType]' => $positionType->id, 'UsersSearch[groupId]' => $group->id]

	]) ?>
<?php endforeach; ?>

<?= BadgeWidget::widget([
	'models' => "Вакансии: ".count($group->relVacancy),
	"badgeOptions" => [
		'class' => (ArrayHelper::getValue($options, 'column_view', false)?"badge pull-right ":"badge pull-left mar-lft ").(count($group->relVacancy) > 0?"badge-danger":"badge-unimportant")
	],
	'linkScheme' => [VacancyModule::to('groups'), 'id' => $group->id]
]) ?>
<?php foreach ($group->getGroupVacancyStatusData() as $key => $vacancyStatus): ?>
	<?= BadgeWidget::widget([
		'models' => (0 === $vacancyStatus->count)?null:"{$vacancyStatus->name}: {$vacancyStatus->count}",
		"badgeOptions" => [
			'style' => $vacancyStatus->style,
			'class' => "badge pull-left"
		],
		'linkScheme' => [VacancyModule::to('groups'), 'id' => $group->id]
	]) ?>
<?php endforeach; ?>


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
						'unbadgedCount' => false,
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