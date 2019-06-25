<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var string $title
 * @var string $leader
 * @var string $logo
 * @var string $leader_role
 * @var int $userCount
 * @var int $vacancyCount
 * @var array $positionData
 */

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
		<?php foreach ($positionData as $positionName => $positionCount): ?>
			<div class="row">
				<div class="col-md-10"><?= $positionName ?></div>
				<div class="col-md-2 badge"><?= $positionCount ?></div>
			</div>
		<?php endforeach; ?>

		<div class="row">
			<div class="col-md-10">Вакансии</div>
			<div class="col-md-2 badge badge-danger"><?= $vacancyCount; ?></div>
		</div>
	</div>
	<div class="panel-footer">
		<?= $leader_role ?>: <?= $leader ?>
	</div>
</div>
