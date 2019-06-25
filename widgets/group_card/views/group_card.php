<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var string $title
 * @var string $leader
 * @var string $logo
 */

use yii\helpers\Html;
use yii\web\View;

?>


<div class="panel col-md-2" style="border-left: 7px solid rgb(236, 240, 245);border-right: 7px solid rgb(236, 240, 245);">
	<div class="panel-heading">
		<div class="panel-control">
			<div class="badge"><?= rand(2, 100); ?></div>
		</div>
		<h3 class="panel-title"><?= Html::encode($title) ?></h3>
	</div>

	<div class="panel-body">
		<div class="row">
			<div class="col-md-10">Бизнес</div>
			<div class="col-md-2 badge"><?= rand(2, 100); ?></div>
		</div>
		<div class="row">
			<div class="col-md-10">IT</div>
			<div class="col-md-2 badge"><?= rand(2, 100); ?></div>
		</div>
		<div class="row">
			<div class="col-md-10">Вакансии</div>
			<div class="col-md-2 badge badge-danger"><?= rand(2, 50); ?></div>
		</div>
	</div>
	<div class="panel-footer">
		<?= $leader ?>
	</div>
</div>
