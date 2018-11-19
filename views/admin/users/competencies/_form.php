<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Users $user
 * @var Competencies $competency
 */

use app\models\competencies\Competencies;
use app\models\users\Users;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
?>
<div class="row">
	<div class="col-xs-12">
		<?php $form = ActiveForm::begin(); ?>
		<div class="panel panel-primary">
			<div class="panel-heading">
				<div class="panel-control">
					<?php if (!$competency->isNewRecord): ?>
						<?= Html::a('Новый', 'create', ['class' => 'btn btn-success']); ?>
					<?php endif; ?>
				</div>
				<h3 class="panel-title"><?= Html::encode($this->title); ?></h3>
			</div>

			<div class="panel-body">
				<div class="row">
				</div>

			</div>

			<div class="panel-footer">
				<div class="btn-group">
					<?= Html::submitButton($competency->isNewRecord?'Сохранить':'Изменить', ['class' => $competency->isNewRecord?'btn btn-success':'btn btn-primary']); ?>
					<?php if ($competency->isNewRecord): ?>
						<?= Html::input('submit', 'more', 'Сохранить и добавить ещё', ['class' => 'btn btn-primary']); ?>
					<?php endif ?>
				</div>
			</div>
		</div>
		<?php ActiveForm::end(); ?>
	</div>
</div>