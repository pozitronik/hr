<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var array $items
 */

use app\helpers\Icons;
use yii\bootstrap\ButtonDropdown;
use yii\web\View;

?>

<?= ButtonDropdown::widget([
	'label' => Icons::menu(),
	'encodeLabel' => false,
	'containerOptions' => [
		'class' => 'dropdown'
	],
	'dropdown' => [
		'options' => [
			'class' => 'pull-left'
		],
		'encodeLabels' => false,
		'items' => $items
	]
]) ?>

