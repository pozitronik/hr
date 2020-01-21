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
 * @var string $ajax_search_url
 */

use app\modules\users\widgets\user_select\UserSelectWidget;
use yii\db\ActiveRecord;
use yii\web\JsExpression;
use yii\web\View;
use kartik\select2\Select2;

?>

<?= Select2::widget([
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
				'data' => new JsExpression("function(params) { return {term:params.term, page: params.page}; }")
			]
		]:[])
]) ?>

