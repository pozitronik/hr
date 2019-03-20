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

use app\helpers\Icons;
use app\modules\users\widgets\user_select\UserSelectWidget;
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
			'content' => Html::submitButton(Icons::add(), ['class' => 'btn btn-primary', 'disabled' => 'disabled']),
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
			'language' => 'ru',
			'templateResult' => new JsExpression('function(item) {return formatUser(item)}'),
			'escapeMarkup' => new JsExpression('function (markup) { return markup; }')
		] + ((UserSelectWidget::DATA_MODE_AJAX === $data_mode)?[//Для аяксового режима добавляем код подгрузки
			'minimumInputLength' => 1,
			'ajax' => [
				'url' => $ajax_search_url,
				'dataType' => 'json',
				'data' => new JsExpression("function(params) { return {term:params.term, page: params.page, group:{$model->primaryKey}}; }")
			]
		]:[]),
	'pluginEvents' => [
		"change.select2" => "function(e) {submit_toggle(e)}"
	]
]); ?>

<?php ActiveForm::end(); ?>