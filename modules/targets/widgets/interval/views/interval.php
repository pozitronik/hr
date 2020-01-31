<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var TargetsPeriods $model
 * @var ActiveForm $form
 */

use app\modules\targets\models\TargetsPeriods;
use kartik\checkbox\CheckboxX;
use kartik\form\ActiveForm;
use yii\web\View;

?>
<div class="row">
	<div class="col-md-2">
		<?= $form->field($model, 'q1')->widget(CheckboxX::class, [
			'pluginOptions' => [
				'threeState' => false
			]
		]) ?>
	</div>
	<div class="col-md-2">
		<?= $form->field($model, 'q2')->widget(CheckboxX::class, [
			'pluginOptions' => [
				'threeState' => false
			]
		]) ?>
	</div>
	<div class="col-md-2">
		<?= $form->field($model, 'q3')->widget(CheckboxX::class, [
			'pluginOptions' => [
				'threeState' => false
			]
		]) ?>
	</div>
	<div class="col-md-2">
		<?= $form->field($model, 'q4')->widget(CheckboxX::class, [
			'pluginOptions' => [
				'threeState' => false
			]
		]) ?>
	</div>
	<div class="col-md-2">
		<?= $form->field($model, 'is_year')->widget(CheckboxX::class, [
			'pluginOptions' => [
				'threeState' => false
			]
		]) ?>
	</div>
</div>