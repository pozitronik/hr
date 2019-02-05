<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var DynamicAttributeProperty $model
 * @var string $attribute
 */

use app\modules\dynamic_attributes\models\DynamicAttributeProperty;
use yii\web\View;
?>

<div class="panel panel-attribute-field">
	<div class="panel-body">
		<div class="row">
			<div class="col-md-12">
				<?= $model->name ?>: <?= $model->$attribute ?>
			</div>
		</div>
	</div>
</div>


