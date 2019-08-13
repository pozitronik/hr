<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Groups $group
 * @var string $leader
 * @var string $leader_role
 */

use app\modules\groups\models\Groups;
use app\modules\salary\models\references\RefUserPositionTypes;
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
	'models' => "{$leader_role}: {$leader}",
	"badgeOptions" => [
		'class' => "badge badge-info pull-right"
	]
]) ?>
