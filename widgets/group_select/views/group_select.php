<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var ActiveRecord $model
 * @var string $attribute ,
 * @var array $data
 * @var boolean $multiple
 * @var array $options
 */

use yii\db\ActiveRecord;
use yii\web\JsExpression;
use yii\web\View;
use kartik\select2\Select2;

?>

<?= Select2::widget([
	'model' => $model,
	'attribute' => $attribute,
	'name' => "group_selection[{$model->id}]",
	'data' => $data,
	'options' => [
		'placeholder' => 'Добавить группу',
		'options' => $options
	],
	'pluginOptions' => [
		'allowClear' => true,
		'multiple' => $multiple,
		'templateResult' => new JsExpression('function(item) {return formatGroup(item)}'),
		'escapeMarkup' => new JsExpression('function (markup) { return markup; }')
	]
]); ?>

