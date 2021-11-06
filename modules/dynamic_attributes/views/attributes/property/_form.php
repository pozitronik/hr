<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var DynamicAttributeProperty $model
 */

use app\components\pozitronik\helpers\ArrayHelper;
use app\modules\dynamic_attributes\models\DynamicAttributeProperty;
use yii\web\View;
use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\select2\Select2;
use kartik\switchinput\SwitchInput;

?>

<?php $form = ActiveForm::begin(); ?>
<div class="panel panel-default">
	<div class="panel-heading">
		<div class="panel-control">
			<?php if (!$model->isNewRecord): ?>
				<?= Html::a('Новый', 'create', ['class' => 'btn btn-success']) ?>
			<?php endif; ?>
		</div>
		<h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
	</div>

	<div class="panel-body">
		<div class="row">
			<div class="col-md-4">
				<?= $form->field($model, 'name') ?>
			</div>
			<div class="col-md-4">
				<?= $form->field($model, 'type')->widget(Select2::class, [
					'data' => ArrayHelper::keymap(DynamicAttributeProperty::PROPERTY_TYPES, 'label')
				]) ?>
			</div>
			<div class="col-md-4">
				<?= $form->field($model, 'required')->widget(SwitchInput::class) ?>
			</div>
		</div>
	</div>

	<div class="panel-footer">
		<div class="btn-group">
			<?= Html::submitButton($model->isNewRecord?'Сохранить':'Изменить', ['class' => $model->isNewRecord?'btn btn-success':'btn btn-primary']) ?>
			<?php if ($model->isNewRecord): ?>
				<?= Html::input('submit', 'more', 'Сохранить и добавить ещё', ['class' => 'btn btn-primary']) ?>
			<?php endif ?>
		</div>
	</div>
</div>
<?php ActiveForm::end(); ?>
