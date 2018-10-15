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
			edgeLabelSize: 'proportional',
			minArrowSize: '10'
		}
	});");
//$this->registerJs("s = sigma.instances()[0]; sigma.plugins.dragNodes(s, s.renderers[0]);", View::POS_LOAD);
?>
<div id="sigma-container"></div>

