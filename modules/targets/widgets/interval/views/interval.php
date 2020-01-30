<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var TargetsIntervals $model
 * @var ActiveForm $form
 */

use app\modules\targets\models\TargetsIntervals;
use kartik\date\DatePicker;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use yii\web\View;

?>
<div class="row">
	<div class="col-md-4">
		<?= $form->field($model, 'start_quarter')->widget(Select2::class, [
			'data' => [
				null => 'Не указано',
				1 => 'Первый квартал',
				2 => 'Второй квартал',
				3 => 'Третий квартал',
				4 => 'Четвёртый квартал'
			]
		]) ?>
	</div>
	<div class="col-md-4">
		<?= $form->field($model, 'finish_quarter')->widget(Select2::class, [
			'data' => [
				null => 'Не указано',
				1 => 'Первый квартал',
				2 => 'Второй квартал',
				3 => 'Третий квартал',
				4 => 'Четвёртый квартал'
			]
		]) ?>
	</div>
	<div class="col-md-4">
		<?= $form->field($model, 'year')->widget(DatePicker::class, [
			'pluginOptions' => [
				'autoclose' => true,
				'format' => 'yyyy',
				'minViewMode' => 'years'
			]
		]) ?>
	</div>
</div>