<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Reference $model
 * @var ActiveForm $form
 */

use app\models\references\Reference;
use yii\web\View;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<div class="row">
	<div class="col-xs-12">
		<div class="panel">
			<div class="panel-heading">
				<div class="panel-control"></div>
				<h3 class="panel-title"><?= Html::encode($this->title); ?></h3>
			</div>
			<?php $form = ActiveForm::begin(); ?>
			<div class="panel-body">
				<?= $form->field($model, 'name')->textInput([
					'maxlength' => true,
					'autofocus' => 'autofocus',
					'spellcheck' => 'true'
				]); ?>
			</div>
			<div class="panel-footer">
				<?= Html::submitButton($model->isNewRecord?'Создать':'Изменить', ['class' => $model->isNewRecord?'btn btn-success':'btn btn-primary']); ?>
			</div>
			<?php ActiveForm::end(); ?>
		</div>
	</div>
</div>