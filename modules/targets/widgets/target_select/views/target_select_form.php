<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var ActiveRecord $model
 * @var string $attribute
 * @var array $data
 * @var int $data_mode
 * @var boolean $multiple
 * @var array $options
 * @var string $formAction
 * @var string $ajax_search_url
 */

use app\helpers\IconsHelper;
use app\modules\groups\widgets\group_select\GroupSelectWidget;
use kartik\form\ActiveForm;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\web\View;
use kartik\select2\Select2;

?>

<?php $form = ActiveForm::begin(['action' => $formAction]); ?>

<?= Select2::widget([
	'addon' => [
		'append' => [
			'content' => Html::submitButton(IconsHelper::add(), ['class' => 'btn btn-primary', 'disabled' => 'disabled']),
			'asButton' => true
		]
	],
	'model' => $model,
	'attribute' => $attribute,
	'data' => $data,
	'options' => [
		'placeholder' => 'Добавить группу',
		'options' => $options
	],
	'pluginOptions' => [
			'allowClear' => true,
			'multiple' => $multiple,
			'templateResult' => (GroupSelectWidget::DATA_MODE_AJAX === $data_mode)?new JsExpression('function(item) {return formatGroupAJAX(item)}'):new JsExpression('function(item) {return formatGroup(item)}'),
			'escapeMarkup' => new JsExpression('function (markup) { return markup; }')
		] + ((GroupSelectWidget::DATA_MODE_AJAX === $data_mode)?[//Для аяксового режима добавляем код подгрузки
			'minimumInputLength' => 1,
			'ajax' => [
				'url' => $ajax_search_url,
				'dataType' => 'json',
				'data' => new JsExpression("function(params) { return {term:params.term, page: params.page}; }")
			]
		]:[]),
	'pluginEvents' => [
		"change.select2" => "function(e) {submit_toggle(e)}"
	]
]) ?>

<?php ActiveForm::end(); ?>