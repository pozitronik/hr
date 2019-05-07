<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var ActiveRecord $model
 * @var array $data
 * @var int $data_mode
 * @var bool $multiple
 * @var string $formAction
 * @var null|string $attribute
 * @var string $ajax_search_url
 */

use app\helpers\IconsHelper;
use app\modules\dynamic_attributes\widgets\attribute_select\AttributeSelectWidget;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\web\View;

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
		'multiple' => $multiple,
		'allowClear' => true,
		'placeholder' => 'Добавить атрибут'
	],
	'pluginOptions' => (AttributeSelectWidget::DATA_MODE_AJAX === $data_mode)?[//Для аяксового режима добавляем код подгрузки
		'minimumInputLength' => 1,
		'ajax' => [
			'url' => $ajax_search_url,
			'dataType' => 'json',
			'data' => new JsExpression("function(params) { return {term:params.term, page: params.page, user:{$model->primaryKey}}; }")
		]
	]:[],
	'pluginEvents' => [
		"change.select2" => "function(e) {submit_toggle(e)}"
	]
])

?>
<?php ActiveForm::end(); ?>