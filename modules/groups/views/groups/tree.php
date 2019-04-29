<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var integer $id
 */

use app\modules\groups\GroupsModule;
use app\modules\groups\models\Groups;
use app\modules\groups\widgets\structure\StructureWidget;
use yii\web\View;

$this->title = 'Граф связей: '.Groups::findModel($id)->name;

$this->params['breadcrumbs'][] = GroupsModule::breadcrumbItem('Группы');
$this->params['breadcrumbs'][] = GroupsModule::breadcrumbItem(Groups::findModel($id)->name, ['groups/profile', 'id' => $id]);

$this->params['breadcrumbs'][] = $this->title;
?>

<?= StructureWidget::widget([
	'id' => $id
]) ?>


