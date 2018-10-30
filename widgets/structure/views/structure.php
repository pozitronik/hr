<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var integer $id
 */

use yii\web\View;

$this->registerJs("init_sigma($id)");
?>
<div id="sigma-container"></div>
<div class="panel" id="control-pane">
	<div class="panel-body">
		<label for="node-labels">Группа</label>
		<select id="node-labels">
			<option value="-1" selected>Все</option>
		</select>
	</div>
	<div class="panel-footer">
		<button class="btn btn-sm btn-warning" id="reset-btn">Сбросить</button>
	</div>
</div>

