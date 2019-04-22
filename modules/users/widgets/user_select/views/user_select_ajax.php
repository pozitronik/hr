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
 * @var string $ajax_post_url
 * @var string $ajax_search_url
 */

use app\helpers\Icons;
use app\modules\users\widgets\user_select\UserSelectWidget;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\web\View;
use kartik\select2\Select2;

?>
<?= Select2::widget([
	'addon' => [
		'append' => [
			'content' => Html::button(Icons::add(), ['id' => 'ajax_post_button', 'class' => 'btn btn-primary', 'disabled' => 'disabled', 'onclick' => "ajax_post('$ajax_post_url', 'ajax_post_button', {$model->primaryKey})"]),
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
				'data' => new JsExpression("function(params) { return {term:params.term, page: params.page}; }")//todo: придумать, как опционально передавать фильтр по группе, если виджет привязан именно к пользователю
			]
		]:[]),
	'pluginEvents' => [
		"change.select2" => "function(e) {ajax_submit_toggle(e,'ajax_post_button')}"
	]
]) ?>
