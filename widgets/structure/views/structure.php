<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var integer $id
 */

use yii\web\View;
$this->registerJs("sigma.parsers.json('graph?id=$id', {
		container: 'sigma-container'
	});")
?>
<div id="sigma-container"></div>
