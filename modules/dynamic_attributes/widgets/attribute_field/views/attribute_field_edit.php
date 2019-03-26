<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var DynamicAttributeProperty $model
 * @var string $attribute
 **/

use app\modules\dynamic_attributes\models\DynamicAttributeProperty;
use yii\web\View;
use kartik\form\ActiveForm;

?>
<?php $form = ActiveForm::begin(); ?>
	<div class="panel panel-attribute-field">
		<div class="panel-heading">
			<div class="panel-title">Панель поля <?= $model->name ?></div>
		</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-md-12">
					<?= $model->editField($form) ?>
				</div>
			</div>
		</div>
	</div>
<?php ActiveForm::end(); ?>