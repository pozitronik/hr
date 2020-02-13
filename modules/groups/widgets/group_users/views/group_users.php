<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Groups $group
 * @var array $options 'column_view':bool -- применить форматирование под "колоночный режим";'compactVacancy':bool -- не показывать разрез по вакансиям в бейджи (будет свёрнут в тултип);'showChildStats':bool -- показывать тултип со статистикой подгрупп
 */

use app\modules\groups\models\Groups;
use app\modules\home\HomeModule;
use app\modules\vacancy\VacancyModule;
use pozitronik\widgets\BadgeWidget;
use pozitronik\helpers\ArrayHelper;
use yii\web\View;

?>
<?= BadgeWidget::widget([
	'models' => "Всего: ".$group->getRelUsers()->active()->countFromCache(),
	'tooltip' => (ArrayHelper::getValue($options, 'showChildStats', false))?(static function($model) use ($group) {
		return BadgeWidget::widget([
			'models' => Groups::getGroupScopeUsersCount($group->collectRecursiveIds()),
			'attribute' => 'dcount',
			'prefix' => 'С подгруппами:',
			'useBadges' => false
		]);
	}):null,
	"badgeOptions" => [
		'class' => "badge badge-info pull-left"
	],
	'linkScheme' => [HomeModule::to(['home/users', 'UsersSearch[groupId]' => $group->id])]

]) ?>
<?php foreach ($group->getGroupPositionTypeData() as $key => $positionType): ?>
	<?= BadgeWidget::widget([
		'models' => /*(0 === $positionType->count && (!ArrayHelper::getValue($options, 'showChildStats', false)))?null:*/ "{$positionType->name}: {$positionType->count}",
		'tooltip' => (ArrayHelper::getValue($options, 'showChildStats', false))?(static function($model) use ($group, $key) {
			return BadgeWidget::widget([
				'models' => 'С подгруппами: '.ArrayHelper::getValue(Groups::getGroupScopePositionTypeData($group->collectRecursiveIds()), "{$key}.count"),
				'useBadges' => false
			]);
		}):null,

		"badgeOptions" => [
			'style' => $positionType->style,
			'class' => 'badge pull-left'
		],
		'linkScheme' => [HomeModule::to(['home/users', 'UsersSearch[positionType]' => $positionType->id, 'UsersSearch[groupId]' => $group->id])]

	]) ?>
<?php endforeach; ?>

<?= BadgeWidget::widget([
	'models' => "Вакансии: ".count($group->relVacancy),
	"badgeOptions" => [
		'class' => (ArrayHelper::getValue($options, 'column_view', false)?"badge pull-right ":"badge pull-left mar-lft ").(count($group->relVacancy) > 0?"badge-danger":"badge-unimportant")
	],
	'tooltip' => ((ArrayHelper::getValue($options, 'compactVacancy', true) & (count($group->relVacancy) > 0)))?(static function($model) use ($group) {
		$hintData = [];
		foreach ($group->getGroupVacancyStatusData() as $key => $vacancyStatus) {
			$hintData[] = BadgeWidget::widget([
				'models' => (0 === $vacancyStatus->count)?null:"{$vacancyStatus->name}: {$vacancyStatus->count}",
				'useBadges' => false
			]);
		}
		return implode(', ', $hintData);
	}):null,
	'linkScheme' => [VacancyModule::to('groups'), 'id' => $group->id]
]) ?>
<?php if (!ArrayHelper::getValue($options, 'compactVacancy', true)): ?>
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
<?php endif; ?>
