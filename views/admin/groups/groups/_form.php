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
				</div>
				<h3 class="panel-title"><?= Html::encode($this->title); ?></h3>
			</div>

			<div class="panel-body">
				<div class="row">
					<div class="col-md-12">
						<?= $form->field($model, 'name')->textInput(['maxlength' => 512]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<?= $form->field($model, 'comment')->textarea(); ?>
					</div>
				</div>
			</div>
			<?= $this->render('chunks/_child_groups.php', [
				'model' => $model
			]); ?>
			<div class="panel-footer">
				<div class="btn-group">
					<?= Html::submitButton($model->isNewRecord?'Добавить':'Изменить информацию', ['class' => $model->isNewRecord?'btn btn-success':'btn btn-primary']); ?>
				</div>
			</div>
		</div>
	</div>
	<?php ActiveForm::end(); ?>
</div>