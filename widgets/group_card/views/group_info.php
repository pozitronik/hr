<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var string $title
 * @var string $leader
 * @var string $logo
 * @var string $leader_role
 * @var int $groupId
 * @var int $userCount
 * @var int $vacancyCount
 * @var array $positionTypeData
 */

use app\modules\salary\models\references\RefUserPositionTypes;
use app\modules\vacancy\VacancyModule;
use app\widgets\badge\BadgeWidget;
use yii\web\View;

?>
<?= BadgeWidget::widget([
	'models' => "Всего: {$userCount}",
	"badgeOptions" => [
		'class' => "badge badge-info"
	],
	'linkScheme' => ['users', 'UsersSearch[groupId]' => $groupId]

]) ?>

<?php foreach ($positionTypeData as $positionId => $positionCount): ?>
	<?php /** @var RefUserPositionTypes $positionType */
	$positionType = RefUserPositionTypes::findModel($positionId); ?>
	<?= BadgeWidget::widget([
		'models' => "{$positionType->name}: {$positionCount}",
		"badgeOptions" => [
			'style' => "background: {$positionType->color}; color: $positionType->textcolor"
		],
		'linkScheme' => ['users', 'UsersSearch[positionType]' => $positionId, 'UsersSearch[groupId]' => $groupId]

	]) ?>

<?php endforeach; ?>

<?= BadgeWidget::widget([
	'models' => "Вакансии: {$vacancyCount}",
	"badgeOptions" => [
		'class' => "badge badge-danger"
	],
	'linkScheme' => [VacancyModule::to('groups'), 'id' => $groupId]
]) ?>

<?= BadgeWidget::widget([
	'models' => "{$leader_role}: {$leader}",
	"badgeOptions" => [
		'class' => "badge badge-info pull-right"
	]
]) ?>
