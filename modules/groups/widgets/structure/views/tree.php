<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var integer $id
 */

use app\modules\groups\widgets\graph_widgets\position_selector\PositionSelectorWidget;
use yii\web\View;

$this->registerJs("init_tree($id);");
?>

<div class="panel" id="controls-block">
	<div class="panel-body">
		<?= PositionSelectorWidget::widget() ?>
	</div>
</div>
<div id="tree-container"></div>
<button id="toggle-controls" class="hidden">Настройки</button>