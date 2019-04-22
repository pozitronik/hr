<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Vacancy $model
 */

use app\models\core\core_module\CoreModule;
use app\modules\vacancy\models\Vacancy;
use app\modules\vacancy\widgets\navigation_menu\VacancyMenuWidget;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

$this->title = $model->isNewRecord?'Создание вакансии':"Просмотр вакансии {$model->name}";
$this->params['breadcrumbs'][] = CoreModule::breadcrumbItem('Вакансии');
$this->params['breadcrumbs'][] = $this->title;

?>

<?php $form = ActiveForm::begin(); ?>
<div class="panel panel-default">
	<div class="panel-heading">
		<div class="panel-control">
			<?= VacancyMenuWidget::widget([
				'model' => $model
			]) ?>
		</div>
		<h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
	</div>

	<div class="panel-body">
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

</div>
<?php ActiveForm::end(); ?>
