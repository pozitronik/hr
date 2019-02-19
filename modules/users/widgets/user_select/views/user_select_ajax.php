<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var ActiveRecord $model
 * @var string $attribute
 * @var array $data
 * @var boolean $multiple
 * @var array $options
 * @var string $formAction
 */

use app\helpers\Icons;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\web\View;
use kartik\select2\Select2;

?>
<?= Select2::widget([
	'addon' => [
		'append' => [
			'content' => Html::button(Icons::add(), ['class' => 'btn btn-primary', 'disabled' => 'disabled','onclick'=> "ajax_post($formAction, )"]),
			'asButton' => true
		]
	],
	'model' => $model,
	'attribute' => $attribute,
	'data' => $data,
	'options' => [
			'placeholder' => 'Добавить пользователя'
		] + $options,
	'pluginOptions' => [
		'allowClear' => true,
		'multiple' => $multiple,
		'templateResult' => new JsExpression('function(item) {return formatUser(item)}'),
		'escapeMarkup' => new JsExpression('function (markup) { return markup; }')
	],
	'pluginEvents' => [
		"change.select2" => "function(e) {submit_toggle(e)}"
	]
]); ?>
