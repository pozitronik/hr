<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var ImportFos $model
 *
 */

use app\modules\import\models\fos\ImportFos;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

?>

<?php $form = ActiveForm::begin(); ?>
	<div class="panel">

		<div class="panel-body">
			<div class="row">
				<div class="col-md-12">
					<?= $form->field($model, 'uploadFileInstance')->fileInput()->label('Выберите файл') ?>
				</div>
			</div>

		</div>
		<div class="panel-footer">
			<?= Html::submitButton('Загрузить', ['class' => 'btn btn-success pull-right']) ?>
			<div class="clearfix"></div>
		</div>
	</div>
<?php ActiveForm::end(); ?>