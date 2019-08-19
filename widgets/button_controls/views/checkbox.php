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

<?= Html::checkboxList($name, $selection, $items, $options + [
		'item' => static function($index, $label, $name, $checked, $value) {
			return Html::input('checkbox', $name, $value, ['id' => $value, 'class' => 'hidden']).Html::label($label, $value, ['class' => "button $value"]);
		}
	]) ?>

