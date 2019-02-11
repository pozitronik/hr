<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var ActiveRecord $model
 * @var array $data
 * @var bool $multiple
 * @var string $formAction
 * @var null|string $attribute
 */

use app\helpers\Icons;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\web\View;

?>


<?php $form = ActiveForm::begin(['action' => $formAction]); ?>

<?= Select2::widget([
	'addon' => [
		'append' => [
			'content' => Html::submitButton(Icons::add(), ['class' => 'btn btn-primary']),
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
	]
]);

?>
<?php ActiveForm::end(); ?>