<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Groups[] $groups
 */

use app\modules\groups\models\Groups;
use app\widgets\group_card\GroupCardWidget;
use yii\web\View;

?>

<?php foreach ($groups as $group): ?>
	<?= GroupCardWidget::widget([
		'group' => $group
	]) ?>

<?php endforeach; ?>

