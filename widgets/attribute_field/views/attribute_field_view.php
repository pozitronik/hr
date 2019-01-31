<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var DynamicAttributeProperty $model
 * @var string $attribute
 */

use app\models\dynamic_attributes\DynamicAttributeProperty;
use yii\web\View;

$fieldsCount = (count($model->dynamicAttribute->structure));
$mdClass = "col-md-1";
if (1 === $fieldsCount) {
	$mdClass = "col-md-12";
} elseif (2 === $fieldsCount) {
	$mdClass = "col-md-6";
} elseif (3 === $fieldsCount) {
	$mdClass = "col-md-4";
} elseif (4 === $fieldsCount) {
	$mdClass = "col-md-3";
} elseif ($fieldsCount < 8) {
	$mdClass = "col-md-2";
}
?>


<div class="panel panel-score-summary panel-primary">
	<div class="panel-heading">
		<div class="panel-title"><?= $model->name ?></div>
	</div>

	<div class="panel-body">
		<div class="row">
			<div class="<?= $mdClass ?>">
				<?= $model->$attribute ?>
			</div>
		</div>
	</div>
</div>


