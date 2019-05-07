<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var array $items
 */

use app\helpers\IconsHelper;
use yii\bootstrap\ButtonDropdown;
use yii\web\View;

?>

<?= ButtonDropdown::widget([
	'label' => IconsHelper::menu(),
	'encodeLabel' => false,
	'dropdown' => [
		'options' => [
			'class' => 'pull-right'
		],
		'encodeLabels' => false,
		'items' => $items
	]
]) ?>

