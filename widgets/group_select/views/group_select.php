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
	'name' => 'group_id',
	'data' => $data,
	'options' => [
		'placeholder' => 'Добавить группу'
	],
	'pluginOptions' => [
		'multiple' => $multiple,
	]
]); ?>

