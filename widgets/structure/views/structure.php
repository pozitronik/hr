<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var integer $id
 */

use yii\web\View;

$this->registerJs("init_sigma($id);");
?>
<div id="sigma-container"></div>

<div class="panel" id="help-pane">
	<div class="panel-body">
		Ctrl+click/Meta+click => Отфильтровать ближние группы<br />
		Shift+move => Перетащить с нижним уровнем<br />
		Alt+move => Перетащить с верхним уровнем<br />
		Alt+Shift+Move => Перетащить ближние группы
	</div>
</div>
<div class="panel" id="control-pane">
	<div id="toggle-control-size" class="min-btn"><span class="glyphicon glyphicon-minus"></span></div>
	<div class="panel-body">
		<label for="node-labels">Группа</label>
		<select id="node-labels">
			<option value="-1" selected>Все</option>
		</select>
	</div>
	<div class="panel-footer">
		<div class="btn-group">
			<button class="btn btn-xs btn-warning pull-left" id="reset-filter">Сбросить фильтр</button>
			<button class="btn btn-xs btn-warning pull-right" id="reset-graph">Сбросить граф</button>
		</div>
	</div>
</div>
<div class="panel" id="search-pane">
	<div id="toggle-control-size-search" class="min-btn"><span class="glyphicon glyphicon-minus"></span></div>
	<div class="panel-body">
		<input type="text" id="user-search" placeholder="Иван Иванов">
	</div>
</div>
<div id="info-pane">
</div>

