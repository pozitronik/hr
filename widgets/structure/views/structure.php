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
		<label for="node-label">Группа</label>
		<select id="node-label">
			<option value="" selected>Все</option>
		</select>

		<label for="node-category">Тип группы</label>
		<select id="node-category">
			<option value="" selected>Все</option>
		</select>
	</div>
	<div class="panel-footer">
		<button class="btn btn-sm btn-warning" id="reset-btn">Сбросить</button>
	</div>
</div>

