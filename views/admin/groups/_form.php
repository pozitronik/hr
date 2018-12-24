<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Groups $model
 */

use app\helpers\Icons;
use app\models\groups\Groups;
use yii\bootstrap\ButtonDropdown;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use app\models\references\refs\RefGroupTypes;
use kartik\file\FileInput;

//todo: кнопка для реадктирования всех пользователей в иерархии
?>
<div class="row">
	<div class="col-xs-12">
		<?php $form = ActiveForm::begin(); ?>
		<div class="panel">
			<div class="panel-heading">
				<div class="panel-control">
					<?php if (!$model->isNewRecord): ?>
						<?= ButtonDropdown::widget([
							'options' => [
								'class' => 'btn-lg btn-default',
							],
							'label' => Icons::menu(),
							'encodeLabel' => false,
							'dropdown' => [
								'options' => [
									'class' => 'pull-right'
								],
								'items' => [
									[
										'label' => 'Новая группа',
										'url' => 'create'
									],
									[
										'label' => 'Граф структуры',
										'url' => ['tree', 'id' => $model->id]
									],
									[
										'label' => 'Редактировать пользователей',
										'url' => ['admin/bunch/index', 'group_id' => $model->id]
									],
									[
										'label' => 'Редактировать пользователей (всех)',
										'url' => ['admin/bunch/index', 'group_id' => $model->id, 'hierarchy' => true]
									]
								]
							]
						]) ?>
					<?php endif; ?>
				</div>
				<h3 class="panel-title"><?= Html::encode($this->title); ?></h3>
			</div>

			<div class="panel-body">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Общие параметры группы</h3>
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
					</div>
				</div>


				<div class="row">
					<div class="col-md-6">

						<?= $this->render('parent_groups/index', [
							'model' => $model,
							'heading' => '<label class="control-label">Родительские группы</label>'
						]); ?>
					</div>
					<div class="col-md-6">
						<?= $this->render('child_groups/index', [
							'model' => $model,
							'heading' => '<label class="control-label">Дочерние группы</label>'
						]); ?>
					</div>
				</div>


				<div class="row">
					<div class="col-md-12">
						<?= $this->render('users/index', [
							'model' => $model,
							'heading' => '<label class="control-label">Пользователи в группе'.Html::a('Иерархия', ['/admin/groups/users-hierarchy', 'id' => $model->id], ['class' => 'btn btn-xs btn-info', 'style' => 'margin-left:15px']).Html::a('Иерархия (с ролями)', ['/admin/groups/users-hierarchy', 'id' => $model->id, 'showRolesSelector' => true], ['class' => 'btn btn-xs btn-warning', 'style' => 'margin-left:5px']).'</label>',
							'selectorInPanel' => true,
							'showRolesSelector' => true,
							'showDropColumn' => true
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