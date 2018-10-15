<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var integer $id
 */

use yii\web\View;

$this->registerJs("sigma.parsers.json('graph?id=$id', {
		renderer: {
			container: document.getElementById('sigma-container'),
			type: 'canvas'
		},
		settings: {
			edgeLabelSize: 'proportional'
		}
	});")
?>
<div id="sigma-container"></div>

