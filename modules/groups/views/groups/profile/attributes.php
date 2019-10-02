<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Groups $model
 * @var DynamicAttributes[] $aggregatedAttributes
 */

use app\modules\dynamic_attributes\models\DynamicAttributes;
use app\modules\dynamic_attributes\widgets\dynamic_attribute\DynamicAttributeWidget;
use app\modules\groups\models\Groups;
use yii\web\View;

?>

<?php foreach ($aggregatedAttributes as $attribute): ?>
	<?php if ([] !== $attribute->getVirtualProperties()): ?>
		<?= DynamicAttributeWidget::widget([
			'attribute' => $attribute
		]); ?>
	<?php endif; ?>
<?php endforeach; ?>