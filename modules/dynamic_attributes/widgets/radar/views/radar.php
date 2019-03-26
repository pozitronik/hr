<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var array $data
 */

use dosamigos\chartjs\ChartJs;
use yii\web\View;

?>
<?= ChartJs::widget([
	'type' => 'radar',
	'options' => [
		'height' => 200,
		'width' => 600
	],
	'data' => $data
]) ?>

