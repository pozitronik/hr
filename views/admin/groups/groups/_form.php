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
use app\models\references\refs\RefGroupTypes;

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
						<?= $form->field($model, 'type')->widget(Select2::class, [
							'data' => RefGroupTypes::mapData(),
							'options' => ['placeholder' => 'Выберите тип'],
							'pluginOptions' => [
								'allowClear' => true
							]
						]); ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<?= $form->field($model, 'comment')->textarea(); ?>
					</div>
				</div>
			</div>
			<?= $this->render('chunks/_parent_groups.php', [
				'model' => $model
			]); ?>
			<?= $this->render('chunks/_child_groups.php', [
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