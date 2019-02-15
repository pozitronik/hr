<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Groups $model
 */

use app\modules\groups\models\Groups;
use app\modules\groups\widgets\navigation_menu\GroupNavigationMenuWidget;
use app\modules\references\widgets\reference_select\ReferenceSelectWidget;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
use app\modules\references\models\refs\RefGroupTypes;
use kartik\file\FileInput;

$this->title = $model->isNewRecord?'Добавление группы':"Профиль группы {$model->name}";
$this->params['breadcrumbs'][] = ['label' => 'Управление', 'url' => ['/admin']];
$this->params['breadcrumbs'][] = ['label' => 'Группы', 'url' => ['/groups/groups']];
$this->params['breadcrumbs'][] = $this->title;

?>

<?php $form = ActiveForm::begin(); ?>
<div class="panel panel-default">
	<div class="panel-heading">
		<div class="panel-control">
			<?= GroupNavigationMenuWidget::widget([
				'model' => $model
			]); ?>
		</div>
		<h3 class="panel-title"><?= Html::encode($this->title); ?></h3>
	</div>

	<div class="panel-body">
		<div class="row">
			<div class="col-md-3">
				<?= $form->field($model, 'upload_image')->widget(FileInput::class, [
					'options' => [
						'accept' => 'image/*',
						'multiple' => false
					],
					'pluginOptions' => [
						'initialPreview' => !empty($model->logotype)?[
							$model->logo
						]:false,
						'initialPreviewAsData' => true,
						'browseClass' => 'btn btn-primary pull-right',
						'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ',
						'browseLabel' => 'Выберите изображение',
						'showCaption' => false
					]
				]) ?>
			</div>

			<div class="col-md-9">
				<div class="row">

					<div class="col-md-12">
						<?= $form->field($model, 'name')->textInput(['maxlength' => 512]); ?>
					</div>

					<div class="col-md-12">
						<?= $form->field($model, 'type')->widget(ReferenceSelectWidget::class, [
							'referenceClass' => RefGroupTypes::class,
							'options' => ['placeholder' => 'Выберите тип'],
							'pluginOptions' => [
								'allowClear' => true
							]
						]); ?>
					</div>

					<div class="col-md-12">
						<?= $form->field($model, 'comment')->textarea(); ?>
					</div>
				</div>
			</div>
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

</div>
<?php ActiveForm::end(); ?>
