<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var WigetableController[] $controllers
 */

use yii\web\View;
use app\widgets\controller\ControllerWidget;
?>

<div class="col-sm-12 col-md-3">
	<p class="dropdown-header">Управление</p>
	<ul class="list-unstyled list-inline text-justify">
		<?php foreach ($controllers as $controller): ?>
		<li class="pad-btm">
			<?= ControllerWidget::widget([
				'model' => $controller
			]); ?>
		</li>
		<?php endforeach; ?>

	</ul>
</div>
