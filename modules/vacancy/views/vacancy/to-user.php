<?php
declare(strict_types = 1);

/**
 * @var Vacancy $model
 * @var View $this
 */

use app\modules\users\models\references\RefUserRoles;
use app\modules\vacancy\models\Vacancy;
use app\modules\vacancy\VacancyModule;
use app\modules\vacancy\widgets\navigation_menu\VacancyNavigationMenuWidget;
use app\widgets\badge\BadgeWidget;
use kartik\detail\DetailView;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

$this->title = 'Заполнение вакансии';
$this->params['breadcrumbs'][] = VacancyModule::breadcrumbItem('Вакансии');
$this->params['breadcrumbs'][] = $this->title;
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

	<div class="panel-body"><!-- Main form panel -->

		<?= DetailView::widget([
			'model' => $model,
			'attributes' => [
				[
					'columns' => [
						[
							'attribute' => 'vacancy_id',
						],
						[
							'attribute' => 'ticket_id',
						],
						[
							'attribute' => 'recruiter',
							'value' => $model->relRefVacancyRecruiter->name
						],
					]
				],
				[
					'columns' => [
						[
							'attribute' => 'create_date',
							'format' => 'date',
						],
						[
							'attribute' => 'estimated_close_date',
							'format' => 'date',
						],
					]
				],
				[
					'columns' => [
						[
							'attribute' => 'group',
							'value' => $model->relGroup->name,
						],
						[
							'attribute' => 'position',
							'value' => $model->relRefUserPosition->name,
						],
						[
							'label' => 'Назначение/роль',
							'format' => 'raw',
							'value' => BadgeWidget::widget([
								'data' => $model->getRelRefUserRoles()->all(),//здесь нельзя использовать свойство, т.к. фреймворк не подгружает все релейшены в $_related сразу. Выяснено экспериментально, на более подробные разбирательства нет времени
								'useBadges' => true,
								'attribute' => 'name',
								'unbadgedCount' => 6,
								"itemsSeparator" => false,
								"optionsMap" => static function() {
									return RefUserRoles::colorStyleOptions();
								}
							])
						],
					]
				],
				[
					'columns' => [
						[
							'attribute' => 'grade',
							'value' => $model->relRefGrade->name,
						],
						[
							'attribute' => 'premium_group',
							'value' => $model->relRefSalaryPremiumGroup->name,
						],
						[
							'attribute' => 'location',
							'value' => $model->relRefLocation->name
						],
					]
				],
				[
					'columns' => [
						[
							'attribute' => 'employer',
							'value' => $model->relEmployer->username,
						],
						[
							'attribute' => 'teamlead',
							'value' => $model->relTeamlead->username,
						],
					]
				],
			]
		]); ?>

		<div class="row">
			<div class="col-md-12">
				<?= $form->field($model, 'username')->textInput(['maxlength' => 50]); ?>
			</div>
		</div>

	</div>

	<div class="panel-footer">
		<div class="btn-group">
			<?= Html::submitButton('Завершить найм', ['class' => 'btn btn-success']) ?>
		</div>
	</div>

</div>
<?php ActiveForm::end(); ?>
