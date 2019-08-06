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
use yii\helpers\Html;
use yii\web\View;

?>


<div class="panel panel-card">
	<div class="panel-heading">
		<div class="panel-control">
			<?= BadgeWidget::widget([
				'models' => $userCount,
				"badgeOptions" => [
					'class' => "badge badge-info"
				],
				'linkScheme' => ['users', 'UsersSearch[groupId]' => $groupId]

			]) ?>
		</div>
		<h3 class="panel-title"><?= Html::encode($title) ?></h3>
	</div>

	<div class="panel-body">
		<?php foreach ($positionTypeData as $positionId => $positionCount): ?>
			<?php /** @var RefUserPositionTypes $positionType */
			$positionType = RefUserPositionTypes::findModel($positionId); ?>
			<div class="row">
				<div class="col-md-10"><?= BadgeWidget::widget([
						'models' => $positionType->name,
						"badgeOptions" => [
							'style' => "float:left; background: {$positionType->color}; color: $positionType->textcolor"
						],
						'linkScheme' => ['users', 'UsersSearch[positionType]' => $positionId, 'UsersSearch[groupId]' => $groupId]

					]) ?></div>
				<div class="col-md-2 pad-no">
					<?= BadgeWidget::widget([
						'models' => $positionCount,
						"badgeOptions" => [
							'style' => "float:right; background: {$positionType->color}; color: $positionType->textcolor"
						],
						'linkScheme' => ['users', 'UsersSearch[positionType]' => $positionId, 'UsersSearch[groupId]' => $groupId]

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
					'linkScheme' => [VacancyModule::to('groups'), 'id' => $groupId]
				]) ?></div>
			<div class="col-md-2 pad-no">
				<?= BadgeWidget::widget([
					'models' => $vacancyCount,
					"badgeOptions" => [
						'class' => "badge badge-danger pull-right"
					],
					'linkScheme' => [VacancyModule::to('groups'), 'id' => $groupId]
				]) ?>
			</div>
		</div>
	</div>
	<div class="panel-footer">
		<?= BadgeWidget::widget([
			'models' => "{$leader_role}: {$leader}",
			"badgeOptions" => [
				'class' => "badge badge-info pull-right"
			]
		]) ?>
	</div>
</div>
