<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Targets $model
 */

use app\modules\groups\models\Groups;
use app\modules\groups\widgets\group_select\GroupSelectWidget;
use app\modules\references\widgets\reference_select\ReferenceSelectWidget;
use app\modules\targets\models\references\RefTargetsResults;
use app\modules\targets\models\references\RefTargetsTypes;
use app\modules\targets\models\Targets;
use app\modules\users\models\Users;
use app\modules\users\widgets\user_select\UserSelectWidget;
use kartik\select2\Select2;
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
			</div>
			<div class="row">
				<div class="col-md-12">
					<?= $form->field($model, 'result_type')->widget(ReferenceSelectWidget::class, [
						'referenceClass' => RefTargetsResults::class
					]) ?>
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
			<div class="row">
				<div class="col-md-12">
					<?= $form->field($model, 'relGroups')->widget(Select2::class, [
						'data' => ArrayHelper::map(Groups::find()->all(), 'id', 'name'),
						'options' => ['placeholder' => ''],
						'pluginOptions' => [
							'allowClear' => true,
							'multiple' => true
						],
					]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<?= $form->field($model, 'relUsers')->widget(Select2::class, [
						'data' => ArrayHelper::map(Users::find()->all(), 'id', 'username'),
						'options' => ['placeholder' => ''],
						'pluginOptions' => [
							'allowClear' => true,
							'multiple' => true
						],
					]); ?>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">

				</div>
			</div>
			<div class="row">
				<div class="col-md-12">

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