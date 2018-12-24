<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var array $data
 * @var array $value
 * @var integer $groupId
 * @var array $options
 */

use kartik\select2\Select2;
use yii\web\View;
use kartik\spinner\Spinner;
use yii\web\JsExpression;

?>

<?= Select2::widget([
	'data' => $data,
	'name' => "RefGroupTypes[$groupId]",
	'value' => $value,
	'options' => [
		'placeholder' => 'Укажите тип группы',
		'options' => $options
	],
	'pluginOptions' => [
		'allowClear' => true,
		'multiple' => false,
		'templateResult' => new JsExpression('function(item) {return formatItem(item)}'),
		'templateSelection' => new JsExpression('function(item) {return formatSelectedItem(item)}'),
		'escapeMarkup' => new JsExpression('function (markup) { return markup; }')
	],
	'pluginEvents' => [
		"change.select2" => "function(e) {set_group_type($groupId, jQuery(e.target).val())}"
	],
	'addon' => [
		'append' => [
			'content' => Spinner::widget(['preset' => 'small', 'align' => 'right', 'hidden' => true, 'id' => "{$groupId}-type-progress"]),
			'asButton' => false
		]
	]

]);

?>