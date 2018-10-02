<?php
declare(strict_types=1);

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

?>
<div class="row">
	<div class="col-xs-12">
		<?php $form = ActiveForm::begin(); ?>
		<div class="panel">
			<div class="panel-heading">
				<h3 class="panel-title"><?= Html::encode($this->title); ?></h3>
				<div class="panel-control"></div>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-6">
						<?= $form->field($model, 'username')->textInput(['maxlength' => 50]); ?>
					</div>
					<div class="col-md-6">
						<?= $form->field($model, 'login')->textInput(['maxlength' => 50]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-5">
						<?php if (null === $model->salt): ?>
							<?= $form->field($model, 'password')->textInput(['maxlength' => 50])->hint('При входе пользователю будет предложено сменить пароль.'); ?>
						<?php else: ?>
							<?= $form->field($model, 'update_password')->textInput(['maxlength' => 50, 'value' => false])->hint('Пароль пользователя будет сброшен, при входе пользователю будет предложено сменить пароль.'); ?>
						<?php endif; ?>
					</div>
					<div class="col-md-5">
						<?= $form->field($model, 'email')->textInput(['maxlength' => 50]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
					<?= $form->field($model, 'comment')->label('Комментарий пользователя'); ?>
					</div>
				</div>
			</div>
			<div class="panel-footer">
				<div class="btn-group">
					<?= Html::submitButton($model->isNewRecord?'Добавить':'Изменить информацию', ['class' => $model->isNewRecord?'btn btn-success':'btn btn-primary']); ?>
				</div>
			</div>
		</div>
	</div>
	<?php ActiveForm::end(); ?>
</div>