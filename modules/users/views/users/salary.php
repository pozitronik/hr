<?php
declare(strict_types = 1);

/**
 * @var Users $model
 * @var View $this
 */

use app\helpers\ArrayHelper;
use app\models\core\core_module\CoreModule;
use app\modules\references\widgets\reference_dependent_dropdown\RefDepDrop;
use app\modules\salary\models\references\RefGrades;
use app\modules\salary\models\references\RefLocations;
use app\modules\salary\models\references\RefSalaryPremiumGroups;
use app\modules\salary\models\references\RefUserPositions;
use app\modules\references\widgets\reference_select\ReferenceSelectWidget;
use app\modules\users\models\Users;
use app\modules\users\widgets\navigation_menu\UserNavigationMenuWidget;
use yii\helpers\Url;
use yii\web\View;
use kartik\form\ActiveForm;
use yii\helpers\Html;

$this->title = "Зарплатные данные пользователя {$model->username}";
$this->params['breadcrumbs'][] = CoreModule::breadcrumbItem('Люди');
$this->params['breadcrumbs'][] = $this->title;

?>
<?php $form = ActiveForm::begin(); ?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="panel-control">
				<?= UserNavigationMenuWidget::widget([
					'model' => $model
				]) ?>
			</div>
			<h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
		</div>

		<div class="panel-body">
			<div class="row">
				<div class="col-md-3">
					<?= $form->field($model, 'position')->widget(ReferenceSelectWidget::class, [
						'referenceClass' => RefUserPositions::class,
						'options' => ['placeholder' => 'Выберите должность'],
						'pluginOptions' => [
							'allowClear' => true
						]
					]) ?>
				</div>
				<div class="col-md-2">
					<?= $form->field($model, 'relGrade')->widget(RefDepDrop::class, [
						'options' => ['placeholder' => 'Выберите грейд'],
						'referenceClass' => RefGrades::class,
						'data' => null === $model->relUsersSalary->grade_id?ArrayHelper::map(ArrayHelper::getValue($model->relRefUserPositions, 'relGrades'), 'id', 'name'):[$model->relUsersSalary->grade_id => ArrayHelper::getValue($model, 'relGrade.name')],
						'type' => RefDepDrop::TYPE_REFERENCE_SELECT,
						'pluginOptions' => [
							'depends' => ['users-position'],
							'url' => Url::to(['/salary/ajax/get-position-grades']),
							'loadingText' => 'Загружаю грейды'
						]
					]) ?>
				</div>


				<div class="col-md-3">
					<?= $form->field($model, 'relPremiumGroup')->widget(ReferenceSelectWidget::class, [
						'referenceClass' => RefSalaryPremiumGroups::class,
						'options' => ['placeholder' => 'Выберите группу премирования'],
						'pluginOptions' => [
							'allowClear' => true
						]
					]) ?>
				</div>
				<div class="col-md-4">
					<?= $form->field($model, 'relLocation')->widget(ReferenceSelectWidget::class, [
						'referenceClass' => RefLocations::class,
						'options' => ['placeholder' => 'Выберите местонахождение'],
						'pluginOptions' => [
							'allowClear' => true
						]
					]) ?>
				</div>

			</div>
			<div class="row">
				<div class="col-md-12">
					<?php if (null === $salaryFork = $model->relSalaryFork): ?>
						Для этого набора параметров не задана зарплатная вилка.
						<?= Html::a('Задать вилку', ['/salary/salary/create', 'position' => $model->position, 'grade' => $model->relUsersSalary->grade_id, 'premium_group' => $model->relUsersSalary->premium_group_id, 'location' => $model->relUsersSalary->location_id], ['class' => 'btn btn-sm btn-default']) ?>
					<?php else: ?>
						Параметры зарплатной вилки: <?= $salaryFork ?>
					<?php endif; ?>
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