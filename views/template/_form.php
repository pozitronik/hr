<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var ActiveRecord $model
 */

use yii\db\ActiveRecord;
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
					<div class="col-md-6">
						<!-- insert form field here -->
					</div>
					<div class="col-md-6">
						<!-- insert form field here -->
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<!-- insert form field here -->
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