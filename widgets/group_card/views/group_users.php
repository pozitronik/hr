<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Groups $group
 * @var array $options
 * Только сводка по юзерам, без инфы по лидерам
 */

use app\modules\groups\models\Groups;
use app\modules\salary\models\references\RefUserPositionTypes;
use app\modules\vacancy\VacancyModule;
use app\widgets\badge\BadgeWidget;
use pozitronik\helpers\ArrayHelper;
use yii\web\View;

?>
<?= BadgeWidget::widget([
	'models' => "Всего: ".count($group->relUsers),
	"badgeOptions" => [
		'class' => "badge badge-info pull-left"
	],
	'linkScheme' => ['users', 'UsersSearch[groupId]' => $group->id]

]) ?>
<?php foreach ($group->getGroupPositionTypeData() as $positionId => $positionCount): ?>
	<?php /** @var RefUserPositionTypes $positionType */
	$positionType = RefUserPositionTypes::findModel($positionId); ?>
	<?= BadgeWidget::widget([
		'models' => "{$positionType->name}: {$positionCount}",
		"badgeOptions" => [
			'style' => $positionType->style,
			'class' => 'badge pull-left'
		],
		'linkScheme' => ['users', 'UsersSearch[positionType]' => $positionId, 'UsersSearch[groupId]' => $group->id]

	]) ?>
<?php endforeach; ?>
<?= BadgeWidget::widget([
	'models' => "Вакансии: ".count($group->relVacancy),
	"badgeOptions" => [
		'class' => ArrayHelper::getValue($options, 'column_view', false)?"badge badge-danger pull-right":"badge badge-danger"
	],
	'linkScheme' => [VacancyModule::to('groups'), 'id' => $group->id]
]) ?>