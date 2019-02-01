<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var DynamicAttributeProperty $model
 * @var string $attribute
 */

use app\models\dynamic_attributes\DynamicAttributeProperty;
use yii\web\View;
?>


<div class="panel panel-attribute-field">
	<div class="panel-heading">
		<div class="panel-title">Панель поля <?= $model->name ?></div>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-12">
				<?= $model->$attribute ?>
			</div>
		</div>
	</div>
</div>


