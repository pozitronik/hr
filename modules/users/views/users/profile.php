<?php
declare(strict_types = 1);

/**
 * @var Users $model
 * @var View $this
 */

use app\helpers\ArrayHelper;
use app\models\core\core_module\CoreModule;
use app\modules\privileges\models\Privileges;
use app\modules\users\models\Users;
use app\modules\users\widgets\navigation_menu\UserNavigationMenuWidget;
use kartik\select2\Select2;
use yii\web\View;
use kartik\form\ActiveForm;
use yii\helpers\Html;
Use kartik\file\FileInput;

$this->title = $model->isNewRecord?'Добавление пользователя':"Профиль пользователя {$model->username}";
$this->params['breadcrumbs'][] = CoreModule::breadcrumbItem('Люди');
$this->params['breadcrumbs'][] = $this->title;

?>
<?php $form = ActiveForm::begin(); ?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="panel-control">
				<?= UserNavigationMenuWidget::widget([
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
							'initialPreview' => !empty($model->profile_image)?[
								$model->avatar
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
							<?= $form->field($model, 'username')->textInput(['maxlength' => 50]); ?>
						</div>
						<div class="col-md-6">
							<?= $form->field($model, 'login')->textInput(['maxlength' => 50]); ?>
						</div>
						<div class="col-md-6">
							<?php if ($model->isNewRecord): ?>
								<?= $form->field($model, 'password')->textInput(['maxlength' => 50])->hint('При входе пользователю будет предложено сменить пароль.'); ?>
							<?php else: ?>
								<?= $form->field($model, 'update_password')->textInput(['maxlength' => 50, 'value' => false])->hint('Пароль пользователя будет сброшен на введённый.'); ?>
							<?php endif; ?>
						</div>
						<div class="col-md-6">
							<?= $form->field($model, 'email')->textInput(['maxlength' => 50]); ?>
						</div>
						<div class="col-md-6">
							<?= $form->field($model, 'relPrivileges')->widget(Select2::class, [
								'data' => ArrayHelper::map(Privileges::find()->active()->all(), 'id', 'name'),
								'options' => ['placeholder' => 'Выберите привилегии пользователя'],
								'pluginOptions' => [
									'multiple' => true,
									'allowClear' => true
								]
							]); ?>
						</div>

						<div class="col-md-12">
							<?= $form->field($model, 'comment')->label('Комментарий пользователя'); ?>
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