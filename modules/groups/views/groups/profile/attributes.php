<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Groups $model
 */

use app\modules\groups\models\Groups;
use app\widgets\badge\BadgeWidget;
use yii\web\View;
use app\modules\dynamic_attributes\models\DynamicAttributesPropertyCollection;

$collection = new DynamicAttributesPropertyCollection(['userScope' => $model->relUsers]);

foreach ($collection->getAverage() as $averages) {
	echo BadgeWidget::widget([
		'models' => $averages,
		'useBadges' => false,
		'itemsSeparator' => false
	]);
}

?>

