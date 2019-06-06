<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var integer $id
 */

use yii\web\View;

$this->registerJs("init_tree($id);");
?>
<div id="controls-block">

</div>
<div id="tree-container"></div>


