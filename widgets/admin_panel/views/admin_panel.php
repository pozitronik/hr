<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var WigetableController[] $controllers
 */

use app\models\core\WigetableController;
use yii\web\View;
use app\widgets\controller\ControllerWidget;
?>

<div class="col-sm-12 col-md-6 menu-border-left">
	<p class="dropdown-header">Управление</p>
	<ul class="list-unstyled list-inline">
		<?php foreach ($controllers as $controller): ?>
		<li class="pad-btm">
			<?= ControllerWidget::widget([
				'model' => $controller
			]); ?>
		</li>
		<?php endforeach; ?>

	</ul>
</div>
