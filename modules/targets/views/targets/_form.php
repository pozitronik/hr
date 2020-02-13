<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Targets $model
 */

use pozitronik\references\widgets\reference_select\ReferenceSelectWidget;
use app\modules\targets\models\references\RefTargetsTypes;
use app\modules\targets\models\Targets;
use app\modules\targets\widgets\target_select\TargetSelectWidget;
use pozitronik\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

?>

<?php $form = ActiveForm::begin(); ?>
	<div class="panel">
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
				<div class="col-md-2">
					<?= $form->field($model, 'type')->widget(ReferenceSelectWidget::class, [//todo: depdrop
						'referenceClass' => RefTargetsTypes::class
					]) ?>
				</div>
				<div class="col-md-4">
					<?php if (!$model->isNewRecord): ?>
						<?= $form->field($model, 'relParentTarget')->widget(TargetSelectWidget::class, [
							'loadingMode' => TargetSelectWidget::DATA_MODE_LOAD,
							'data' => ([] === $parentTargets = ArrayHelper::map(Targets::find()->where(['type' => ArrayHelper::getValue($model, 'relTargetsTypes.parent')])->all(), 'id', 'name'))?null:$parentTargets
						]) ?>
					<?php endif; ?>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<?= $form->field($model, 'name')->textInput(['maxlength' => 512]) ?>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<?= $form->field($model, 'comment')->textarea() ?>
				</div>
			</div>
		</div>

		<div class="row">
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
<?php ActiveForm::end(); ?>