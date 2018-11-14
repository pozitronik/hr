<?php
declare(strict_types = 1);

/**
 * Шаблон формы страницы изменения информации пользователя
 *
 * @var View $this
 * @var Users $model
 */

use app\models\users\Users;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use app\models\references\refs\RefUserPositions;
use kartik\file\FileInput;
?>
<div class="row">
	<div class="col-xs-12">
		<?php $form = ActiveForm::begin(); ?>
		<div class="panel panel-primary">
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
					<div class="col-md-3">
						<?= $form->field($model, 'profile_image')->widget(FileInput::class, [
							'options' => [
								'accept' => 'image/*',
								'multiple' => false
							],
							'pluginOptions' => [
								'initialPreview' => !empty($model->profile_image)?[
									$model->avatar
								]:false,
								'initialPreviewAsData' => true
							]
						]) ?>
					</div>
					<div class="col-md-9">
						<div class="row">
							<div class="col-md-12">
								<?= $form->field($model, 'username')->textInput(['maxlength' => 50]); ?>
							</div>
							<div class="col-md-6">
								<?= $form->field($model, 'login')->textInput(['maxlength' => 50]); ?>
							</div>
							<div class="col-md-6">
								<?php if (null === $model->salt): ?>
									<?= $form->field($model, 'password')->textInput(['maxlength' => 50])->hint('При входе пользователю будет предложено сменить пароль.'); ?>
								<?php else: ?>
									<?= $form->field($model, 'update_password')->textInput(['maxlength' => 50, 'value' => false])->hint('Пароль пользователя будет сброшен на введённый.'); ?>
								<?php endif; ?>
							</div>
							<div class="col-md-12">
								<?= $form->field($model, 'email')->textInput(['maxlength' => 50]); ?>
							</div>

							<div class="col-md-12">
								<?= $form->field($model, 'comment')->label('Комментарий пользователя'); ?>
							</div>

							<div class="col-md-12">
								<?= $form->field($model, 'position')->widget(Select2::class, [
									'data' => RefUserPositions::mapData(),
									'options' => ['placeholder' => 'Выберите роль'],
									'pluginOptions' => [
										'allowClear' => true
									]
								]); ?>
							</div>
						</div>
					</div>
				</div>


			</div>
			<?= $this->render('chunks/_groups.php', [
				'model' => $model
			]); ?>
			<div class="panel-footer">
				<div class="btn-group">
					<?= Html::submitButton($model->isNewRecord?'Сохранить':'Изменить', ['class' => $model->isNewRecord?'btn btn-success':'btn btn-primary']); ?>
					<?php if ($model->isNewRecord): ?>
						<?= Html::input('submit', 'more', 'Сохранить и добавить ещё', ['class' => 'btn btn-primary']); ?>
					<?php endif ?>
				</div>
			</div>
		</div>
	</div>
	<?php ActiveForm::end(); ?>
</div>