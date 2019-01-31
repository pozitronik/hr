<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var DynamicAttributes $dynamicAttribute
 * @var DynamicAttributeProperty[] $userProperties
 */

use app\models\dynamic_attributes\DynamicAttributeProperty;
use app\models\dynamic_attributes\DynamicAttributes;
use yii\web\View;

$fieldsCount = (count($userProperties));
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

<div class="panel panel-score-summary panel-default">
	<div class="panel-heading">
		<div class="panel-title"><?= $dynamicAttribute->name ?></div>
	</div>

	<div class="panel-body">
		<div class="row">
			<?php foreach ($userProperties as $userProperty): ?>
				<div class="<?= $mdClass ?>">
					<?= $userProperty->widget([
						'attribute' => 'value',
						'readOnly' => true,
						'showEmpty' => false
					]); ?>
				</div>
			<?php endforeach; ?>

		</div>
	</div>
</div>

