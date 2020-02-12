<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var WigetableController[] $controllers
 * @var integer $mode
 */

use pozitronik\core\models\core_controller\WigetableController;
use yii\web\View;
use app\widgets\controller\ControllerWidget;

?>

<div class="col-sm-12 col-md-6 menu-border-left">
	<p class="dropdown-header">Управление</p>
	<ul class="list-unstyled list-inline">
		<?php foreach ($controllers as $controller): ?>
			<?php if (!$controller->menuDisabled): ?>
				<li class="pad-btm">
					<?= ControllerWidget::widget([
						'model' => $controller,
						'mode' => $mode
					]) ?>
				</li>
			<?php endif; ?>
		<?php endforeach; ?>
	</ul>
</div>
