<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Vacancy $model
 */

use app\modules\groups\widgets\group_select\GroupSelectWidget;
use app\modules\references\widgets\reference_select\ReferenceSelectWidget;
use app\modules\salary\models\references\RefLocations;
use app\modules\salary\models\references\RefUserPositions;
use app\modules\users\widgets\user_select\UserSelectWidget;
use app\modules\vacancy\models\references\RefVacancyRecruiters;
use app\modules\vacancy\models\references\RefVacancyStatuses;
use app\modules\vacancy\models\Vacancy;
use app\modules\vacancy\widgets\navigation_menu\VacancyNavigationMenuWidget;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

?>

<?php $form = ActiveForm::begin(); ?>
<div class="panel panel-default">
	<div class="panel-heading">
		<div class="panel-control">
			<?= VacancyNavigationMenuWidget::widget([
				'model' => $model
			]) ?>
		</div>
		<h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
	</div>

	<div class="panel-body">
		<div class="row">
			<div class="col-md-1">
				<?= $form->field($model, 'vacancy_id')->textInput() ?>
			</div>
			<div class="col-md-1">
				<?= $form->field($model, 'ticket_id')->textInput() ?>
			</div>
			<div class="col-md-3">
				<?= $form->field($model, 'status')->widget(ReferenceSelectWidget::class, [
					'referenceClass' => RefVacancyStatuses::class,
					'pluginOptions' => [
						'multiple' => false,
						'allowClear' => true
					]
				]) ?>
			</div>
			<div class="col-md-3">
				<?= $form->field($model, 'recruiter')->widget(ReferenceSelectWidget::class, [
					'referenceClass' => RefVacancyRecruiters::class,
					'pluginOptions' => [
						'multiple' => false,
						'allowClear' => true
					]
				]) ?>
			</div>
		</div>
		<div class="row">
			<div class="col-md-3">
				<?= $form->field($model, 'group')->widget(GroupSelectWidget::class, [
					'multiple' => false,
					'mode' => GroupSelectWidget::MODE_FIELD,
					'dataMode' => $model->isNewRecord?GroupSelectWidget::DATA_MODE_AJAX:GroupSelectWidget::DATA_MODE_LOAD
				]) ?>
			</div>
			<div class="col-md-3">
				<?= $form->field($model, 'position')->widget(ReferenceSelectWidget::class, [
					'referenceClass' => RefUserPositions::class,
					'pluginOptions' => [
						'multiple' => false
					]
				]) ?>
			</div>
			<div class="col-md-3">
				<?= $form->field($model, 'location')->widget(ReferenceSelectWidget::class, [
					'referenceClass' => RefLocations::class,
					'pluginOptions' => [
						'multiple' => false
					]
				]) ?>
			</div>
			<div class="col-md-3">
				<?= $form->field($model, 'employer')->widget(UserSelectWidget::class, [
					'multiple' => false,
					'mode' => GroupSelectWidget::MODE_FIELD,
					'dataMode' => $model->isNewRecord?GroupSelectWidget::DATA_MODE_AJAX:GroupSelectWidget::DATA_MODE_LOAD
				]) ?>
			</div>
		</div>
		<div class="row">
			<div class="col-md-3">
				<?= $form->field($model, 'teamlead')->widget(UserSelectWidget::class, [
					'multiple' => false,
					'mode' => GroupSelectWidget::MODE_FIELD,
					'dataMode' => $model->isNewRecord?GroupSelectWidget::DATA_MODE_AJAX:GroupSelectWidget::DATA_MODE_LOAD
				]) ?>
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
