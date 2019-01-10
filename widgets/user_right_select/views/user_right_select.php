<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var \yii\db\ActiveRecord $model
 * @var string $attribute,
 * @var array $data
 * @var boolean $multiple
 */

use yii\web\View;
use kartik\select2\Select2;

?>

<?= Select2::widget([
	'model' => $model,
	'attribute' => $attribute,
	'name' => 'right_class',
	'data' => $data,
	'options' => [
		'multiple' => $multiple,
		'placeholder' => 'Добавить право'
	]
]); ?>

