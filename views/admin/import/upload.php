<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var ImportFos $model
 *
 */

use app\models\imports\ImportFos;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

?>

<?php $form = ActiveForm::begin(); ?>
	<div class="panel">

		<div class="panel-body">
			<div class="row">
				<div class="col-md-12">
					<?= $form->field($model, 'uploadFileInstance')->fileInput()->label('Выберите файл'); ?>
				</div>
			</div>

		</div>
		<div class="panel-footer">
			<div class="btn-group">
				<?= Html::submitButton('Загрузить', ['class' => 'btn btn-success']); ?>
			</div>
		</div>
	</div>
<?php ActiveForm::end(); ?>