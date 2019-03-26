<?php
declare(strict_types = 1);

/**
 * Шаблон страницы ошибки
 * @var yii\web\View $this
 * @var string $name
 * @var string $message
 * @var Exception $exception
 */

use yii\helpers\Html;

$this->title = $name;
?>
<hr class="new-section-sm bord-no">
<div class="row">
	<div class="col-lg-6 col-lg-offset-3">
		<div class="panel panel-trans text-center">
			<div class="panel-heading">
				<h1 class="error-code text-primary"><?= Html::encode($this->title) ?></h1>
			</div>
			<div class="panel-body">
				<p><?= nl2br(Html::encode($message)) ?></p>
				<div class="pad-top"><a class="btn-link text-semibold" href="/">На главную</a></div>
			</div>
		</div>
	</div>
</div>