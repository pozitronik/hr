<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var array $data
 * @var array $value
 * @var integer $attributeId
 * @var integer $userId
 * @var array $options
 * @var bool $showStatus
 */

use kartik\select2\Select2;
use yii\web\View;
use kartik\spinner\Spinner;
use yii\web\JsExpression;

?>

<?= Select2::widget([
	'data' => $data,
	'name' => "AttributeTypes[$userId]",
	'value' => $value,
	'options' => [
		'placeholder' => 'Укажите тип связи атрибута',
		'options' => $options
	],
	'pluginOptions' => [
		'allowClear' => true,
		'multiple' => true,
		'templateResult' => new JsExpression('function(item) {return formatType(item)}'),
		'templateSelection' => new JsExpression('function(item) {return formatSelectedType(item)}'),
		'escapeMarkup' => new JsExpression('function (markup) { return markup; }')
	],
	'pluginEvents' => [
		"change.select2" => "function(e) {set_types($userId, $attributeId, jQuery(e.target).val())}"
	],
	'addon' => $showStatus?[
		'append' => [
			'content' => Spinner::widget(['preset' => 'small', 'align' => 'right', 'hidden' => true, 'id' => "{$userId}-{$attributeId}-types-progress"]),
			'asButton' => false
		]
	]:false

])

?>