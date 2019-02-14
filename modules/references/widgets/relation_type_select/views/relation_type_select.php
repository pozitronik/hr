<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var array $data
 * @var array $value
 * @var integer $parentGroupId
 * @var integer $childGroupId
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
	'name' => "RefGroupRelationTypes[$parentGroupId]",
	'value' => $value,
	'options' => [
		'placeholder' => 'Укажите тип связи',
		'options' => $options
	],
	'pluginOptions' => [
		'allowClear' => true,
		'multiple' => false,
		'templateResult' => new JsExpression('function(item) {return formatGroupRelation(item)}'),
		'templateSelection' => new JsExpression('function(item) {return formatSelectedGroupRelation(item)}'),
		'escapeMarkup' => new JsExpression('function (markup) { return markup; }')
	],
	'pluginEvents' => [
		"change.select2" => "function(e) {set_group_relation_type($parentGroupId, $childGroupId, jQuery(e.target).val())}"
	],
	'addon' => $showStatus?[
		'append' => [
			'content' => Spinner::widget(['preset' => 'small', 'align' => 'right', 'hidden' => true, 'id' => "{$parentGroupId}-{$childGroupId}-relation-progress"]),
			'asButton' => false
		]
	]:false

]);

?>