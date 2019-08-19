<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var string $name
 * @var array|string|null $selection
 * @var array $options
 * @var array $items
 */

use yii\bootstrap\Html;
use yii\web\View;

?>

<?= Html::radioList($name, null, $items, $options + [
		'item' => function($index, $label, $name, $checked, $value) {
			return Html::input('radio', $name, $value, ['id' => $value, 'class' => 'hidden']).Html::label($label, $value, ['class' => "button $value"]);
		},
	]) ?>

