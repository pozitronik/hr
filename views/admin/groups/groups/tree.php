<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var integer $id
 */

use app\widgets\structure\StructureWidget;
use yii\web\View;

?>

<?= StructureWidget::widget([
	'id' => $id
]); ?>


