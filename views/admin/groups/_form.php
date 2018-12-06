<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Groups $model
 */

use app\models\groups\Groups;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use app\models\references\refs\RefGroupTypes;
use kartik\file\FileInput;

?>
<div class="row">
	<div class="col-xs-12">
		<?php $form = ActiveForm::begin(); ?>
		<div class="panel">
			<div class="panel-heading">
				<div class="panel-control">
					<?php if (!$model->isNewRecord): ?>
						<?= Html::a('Новый', 'create', ['class' => 'btn btn-success']); ?>
					<?php endif; ?>
					<?php if (!$model->isNewRecord): ?>
						<?= Html::a('Граф', ['tree', 'id' => $model->id], ['class' => 'btn btn-info']); ?>
						<?= Html::a('Редактировать пользователей', ['admin/users/mass-update', 'group_id' => $model->id], ['class' => 'btn btn-info']); ?>
					<?php endif; ?>
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
								<?= $form->field($model, 'type')->widget(Select2::class, [
									'data' => RefGroupTypes::mapData(),
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
				<div class="row">
					<div class="col-md-6">
						<label class="control-label">Родительские группы</label>
						<?= $this->render('parent_groups/index', [
							'model' => $model
						]); ?>
					</div>
					<div class="col-md-6">
						<label class="control-label">Дочерние группы</label>
						<?= $this->render('child_groups/index', [
							'model' => $model
						]); ?>
					</div>
				</div>


				<div class="row">
					<div class="col-md-12">
						<label class="control-label">Пользователи в группе</label>
						<?= $this->render('users/index', [
							'model' => $model
						]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<label class="control-label">Пользователи в иерархии</label>
						<?= $this->render('users/index_tree', [
							'model' => $model
						]); ?>
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
	</div>
</div>