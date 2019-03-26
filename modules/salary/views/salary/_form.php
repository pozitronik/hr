<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var SalaryFork $model
 */

use app\helpers\ArrayHelper;
use app\modules\references\widgets\reference_dependent_dropdown\RefDepDrop;
use app\modules\references\widgets\reference_select\ReferenceSelectWidget;
use app\modules\salary\models\references\RefGrades;
use app\modules\salary\models\references\RefLocations;
use app\modules\salary\models\references\RefSalaryPremiumGroups;
use app\modules\salary\models\references\RefUserPositions;
use app\modules\salary\models\SalaryFork;
use kartik\number\NumberControl;
use yii\helpers\Html;
use yii\helpers\Url;
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
					<?= $form->field($model, 'position_id')->widget(ReferenceSelectWidget::class, [
						'referenceClass' => RefUserPositions::class,
						'data' => RefUserPositions::mapByGrade(),
						'options' => ['placeholder' => 'Выберите должность'],
						'pluginOptions' => [
							'allowClear' => true
						]
					]) ?>
				</div>
				<div class="col-md-2">
					<?= $form->field($model, 'grade_id')->widget(RefDepDrop::class, [
						'options' => ['placeholder' => 'Выберите грейд'],
						'referenceClass' => RefGrades::class,
						'data' => [$model->grade_id => ArrayHelper::getValue($model, 'refGrade.name')],
						'type' => RefDepDrop::TYPE_REFERENCE_SELECT,
						'pluginOptions' => [
							'depends' => ['salaryfork-position_id'],
							'url' => Url::to(['ajax/get-position-grades']),
							'loadingText' => 'Загружаю грейды'
						]
					]) ?>
				</div>
				<div class="col-md-2">
					<?= $form->field($model, 'premium_group_id')->widget(ReferenceSelectWidget::class, [
						'referenceClass' => RefSalaryPremiumGroups::class,
						'options' => ['placeholder' => 'Выберите группу премирования'],
						'pluginOptions' => [
							'allowClear' => true
						]
					]) ?>
				</div>
				<div class="col-md-2">
					<?= $form->field($model, 'location_id')->widget(ReferenceSelectWidget::class, [
						'referenceClass' => RefLocations::class,
						'options' => ['placeholder' => 'Выберите расположение'],
						'pluginOptions' => [
							'allowClear' => true
						]
					]) ?>
				</div>
				<div class="col-md-2">
					<?= $form->field($model, 'min')->widget(NumberControl::class, [
						'maskedInputOptions' => [
							'suffix' => ' ₽',
							'allowMinus' => false
						],
						'displayOptions' => [
							'class' => 'form-control kv-monospace',
							'placeholder' => 'Укажите значение'
						]
					]) ?>
				</div>
				<div class="col-md-2">
					<?= $form->field($model, 'max')->widget(NumberControl::class, [
						'maskedInputOptions' => [
							'suffix' => ' ₽',
							'allowMinus' => false
						],
						'displayOptions' => [
							'class' => 'form-control kv-monospace',
							'placeholder' => 'Укажите значение'
						]
					]) ?>
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