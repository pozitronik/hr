<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Groups $model
 */

use app\modules\dynamic_attributes\widgets\dynamic_attribute\DynamicAttributeWidget;
use app\modules\groups\models\Groups;
use yii\web\View;
use app\modules\dynamic_attributes\models\DynamicAttributesPropertyCollection;

$collection = new DynamicAttributesPropertyCollection(['userScope' => $model->relUsers]);

foreach ($collection->getAverage() as $attribute) {
	if ([] !== $attribute->getVirtualProperties()) {
		echo DynamicAttributeWidget::widget([
			'attribute' => $attribute
		]);
	}

}

?>

