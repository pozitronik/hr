<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Groups[] $groups
 */

use app\modules\groups\models\Groups;
use app\widgets\group_card\GroupCardWidget;
use yii\web\View;
$this->title = 'Мои группы';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php foreach ($groups as $group): ?>
	<?= GroupCardWidget::widget([
		'group' => $group
	]) ?>

<?php endforeach; ?>

