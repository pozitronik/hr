<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var ActiveRecord $model
 * @var $data array
 * @var $multiple bool
 * @var $formAction string
 * @var $attribute null|string
 */

use kartik\select2\Select2;
use yii\db\ActiveRecord;
use yii\web\View;

?>

<?= Select2::widget([
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

