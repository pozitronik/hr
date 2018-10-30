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
<div id="control-pane">
	<h2 class="underline">filters</h2>

	<div>
		<h3>min degree <span id="min-degree-val">0</span></h3>
		0 <input id="min-degree" type="range" min="0" max="0" value="0"> <span id="max-degree-value">0</span><br>
	</div>
	<div>
		<h3>node category</h3>
		<select id="node-category">
			<option value="" selected>All categories</option>
		</select>
	</div>
	<span class="line"></span>
	<div>
		<button id="reset-btn">Reset filters</button>
		<button id="export-btn">Export</button>
	</div>
	<div id="dump" class="hidden"></div>
</div>

