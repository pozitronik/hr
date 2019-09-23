<?php
declare(strict_types = 1);

/* @var View $this */

use app\modules\groups\models\Groups;
use app\modules\groups\widgets\group_card\GroupCardWidget;
use yii\web\View;

?>

<?= GroupCardWidget::widget(['group' => Groups::findModel(7)]) ?>