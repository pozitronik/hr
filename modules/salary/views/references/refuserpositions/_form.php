<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var RefUserRoles $model
 * @var ActiveForm $form
 */

use app\modules\salary\models\references\RefGrades;
use app\modules\salary\models\references\RefUserPositionBranches;
use app\modules\salary\models\references\RefUserPositionTypes;
use app\modules\users\models\references\RefUserRoles;
use app\modules\references\widgets\reference_select\ReferenceSelectWidget;
use kartik\color\ColorInput;
use yii\web\View;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<div class="panel">
	<div class="panel-heading">
		<div class="panel-control"></div>
		<h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
	</div>
	<?php $form = ActiveForm::begin(); ?>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-7">
				<?= $form->field($model, 'name')->textInput([
					'maxlength' => true,
					'autofocus' => 'autofocus',
					'spellcheck' => 'true'
				]) ?>
			</div>

			<div class="col-md-5">
				<?= $form->field($model, 'color')->widget(ColorInput::class, [
					'options' => [
						'placeholder' => 'Выбрать цвет'
					],
					'pluginOptions' => [
						'showAlpha' => false,
						'preferredFormat' => 'rgb'
					]
				]) ?>
			</div>

		</div>
		<div class="row">
			<div class="col-md-4">
				<?= $form->field($model, 'types')->widget(ReferenceSelectWidget::class, [
					'referenceClass' => RefUserPositionTypes::class,
					'options' => ['placeholder' => 'Выберите тип'],
					'pluginOptions' => [
						'multiple' => true,
						'allowClear' => true
					]
				]) ?>
			</div>
			<div class="col-md-4">
				<?= $form->field($model, 'branch')->widget(ReferenceSelectWidget::class, [
					'referenceClass' => RefUserPositionBranches::class,
					'options' => ['placeholder' => 'Выберите ветвь'],
					'pluginOptions' => [
						'allowClear' => true
					]
				]) ?>
			</div>
			<div class="col-md-4">
				<?= $form->field($model, 'relGrades')->widget(ReferenceSelectWidget::class, [
					'referenceClass' => RefGrades::class,
					'options' => ['placeholder' => 'Выберите грейды'],
					'pluginOptions' => [
						'multiple' => true,
						'allowClear' => true
					]
				]) ?>
			</div>
		</div>
	</div>
	<div class="panel-footer">
		<?= Html::submitButton($model->isNewRecord?'Создать':'Изменить', ['class' => $model->isNewRecord?'btn btn-success':'btn btn-primary']) ?>
		<?php if ($model->isNewRecord): ?>
			<?= Html::input('submit', 'more', 'Сохранить и добавить ещё', ['class' => 'btn btn-primary']) ?>
		<?php endif ?>
	</div>
	<?php ActiveForm::end(); ?>
</div>
