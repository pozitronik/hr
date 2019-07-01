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

use app\helpers\Utils;
use app\modules\salary\models\references\RefUserPositionTypes;
use app\modules\users\UsersModule;
use app\widgets\badge\BadgeWidget;
use yii\BaseYii;
use yii\helpers\Html;
use yii\web\View;

?>


<div class="panel panel-card col-md-2" style="border-left: 7px solid rgb(236, 240, 245);border-right: 7px solid rgb(236, 240, 245);">
	<div class="panel-heading">
		<div class="panel-control">
			<div class="badge badge-info"><?= $userCount ?></div>
		</div>
		<h3 class="panel-title"><?= Html::encode($title) ?></h3>
	</div>

	<div class="panel-body">
		<?php foreach ($positionTypeData as $positionId => $positionCount): ?>
			<?php /** @var RefUserPositionTypes $positionType */
			$positionType = RefUserPositionTypes::findModel($positionId); ?>
			<div class="row">
				<div class="col-md-10"><?= $positionType->name ?></div>
				<div class="col-md-2 pad-no">
					<?= BadgeWidget::widget([
						'value' => $positionCount,
						"badgeOptions" => [
							'style' => "float:right; background: {$positionType->color}; color: ".Utils::RGBColorContrast($positionType->color)
						],
						'linkScheme' => ['users', 'UsersSearch[positionType]' => $positionId, 'UsersSearch[groupId]' => $groupId]

					]) ?>
				</div>
			</div>
		<?php endforeach; ?>

		<div class="row">
			<div class="col-md-10">Вакансии</div>
			<div class="col-md-2 pad-no">
				<?= BadgeWidget::widget([
					'value' => $vacancyCount,
					"badgeOptions" => [
						'class' => "badge badge-danger pull-right"
					]
				]) ?>
			</div>
		</div>
	</div>
	<div class="panel-footer">
		<?= $leader_role ?>: <?= $leader ?>
	</div>
</div>
