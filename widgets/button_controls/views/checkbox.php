<?php
declare(strict_types = 1);

/**
 * @var View $this
 */

use yii\bootstrap\Html;
use yii\web\View;

echo 'widget button_controls view';

?>

<?= Html::radioList('Сортировка', null, [
	'sort-by-type' => 'По типу',
	'sort-by-count' => 'По сотрудникам',
	'sort-by-vacancy' => 'По вакансиям'
], [
	'item' => function($index, $label, $name, $checked, $value) {
		return Html::input('', $name, $value, ['id' => $value, 'class' => 'hidden']).Html::label($label, $value, ['class' => "button $value"]);
	},
	'class' => 'round-borders btn-group'
]) ?>
