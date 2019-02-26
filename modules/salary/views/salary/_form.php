<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var ActiveRecord $model
 */

use app\modules\references\widgets\reference_select\ReferenceSelectWidget;
use app\modules\salary\models\references\RefGrades;
use app\modules\salary\models\references\RefLocations;
use app\modules\salary\models\references\RefSalaryPremiumGroups;
use app\modules\salary\models\references\RefUserPositions;
use kartik\range\RangeInput;
use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

?>

<?php $form = ActiveForm::begin(); ?>
	<div class="panel">
		<div class="panel-heading">
			<div class="panel-control">
				<?php if (!$model->isNewRecord): ?>
					<?= Html::a('Новый', 'create', ['class' => 'btn btn-success']); ?>
				<?php endif; ?>
			</div>
			<h3 class="panel-title"><?= Html::encode($this->title); ?></h3>
		</div>

		<div class="panel-body">
			<div class="row">
				<div class="col-md-2">
					<?= $form->field($model, 'refUserPosition')->widget(ReferenceSelectWidget::class, [
						'referenceClass' => RefUserPositions::class,
						'options' => ['placeholder' => 'Выберите должность'],
						'pluginOptions' => [
							'allowClear' => true
						]
					]) ?>
				</div>
				<div class="col-md-2">
					<?= $form->field($model, 'refGrade')->widget(ReferenceSelectWidget::class, [
						'referenceClass' => RefGrades::class,
						'options' => ['placeholder' => 'Выберите грейд'],
						'pluginOptions' => [
							'allowClear' => true
						]
					]) ?>
				</div>
				<div class="col-md-2">
					<?= $form->field($model, 'refPremiumGroup')->widget(ReferenceSelectWidget::class, [
						'referenceClass' => RefSalaryPremiumGroups::class,
						'options' => ['placeholder' => 'Выберите группу премирования'],
						'pluginOptions' => [
							'allowClear' => true
						]
					]) ?>
				</div>
				<div class="col-md-2">
					<?= $form->field($model, 'refLocation')->widget(ReferenceSelectWidget::class, [
						'referenceClass' => RefLocations::class,
						'options' => ['placeholder' => 'Выберите расположение'],
						'pluginOptions' => [
							'allowClear' => true
						]
					]) ?>
				</div>
				<div class="col-md-2">
					<?= $form->field($model, 'min')->widget(RangeInput::class, [
						'html5Options' => [
							'min' => 0,
							'max' => 10000000
						],
						'html5Container' => [
							'style' => 'width:50%'
						],
						'addon' => [
						],
						'options' => [
							'placeholder' => 'Укажите значение'
						]
					]); ?>
				</div>
				<?= $form->field($model, 'max')->widget(RangeInput::class, [
					'html5Options' => [
						'min' => 0,
						'max' => 10000000
					],
					'html5Container' => [
						'style' => 'width:50%'
					],
					'addon' => [
					],
					'options' => [
						'placeholder' => 'Укажите значение'
					]
				]); ?>
			</div>
		</div>

		<div class="row">
		</div>
	</div>
	<div class="panel-footer">
		<div class="btn-group">
			<?= Html::submitButton($model->isNewRecord?'Сохранить':'Изменить', ['class' => $model->isNewRecord?'btn btn-success':'btn btn-primary']); ?>
			<?php if ($model->isNewRecord): ?>
				<?= Html::input('submit', 'more', 'Сохранить и добавить ещё', ['class' => 'btn btn-primary']); ?>
			<?php endif ?>
		</div>
	</div>
<?php ActiveForm::end(); ?>