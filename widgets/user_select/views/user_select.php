<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var ActiveRecord $model
 * @var string $attribute,
 * @var array $data
 * @var boolean $multiple
 * @var string $placeholder
 */

use yii\db\ActiveRecord;
use yii\web\View;
use kartik\select2\Select2;

?>

<?= Select2::widget([
	'model' => $model,
	'attribute' => $attribute,
	'name' => 'user_id',
	'data' => $data,
	'options' => [
		'multiple' => $multiple,
		'placeholder' => $placeholder
	]
]); ?>

