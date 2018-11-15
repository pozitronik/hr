<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var integer $id
 */

use app\models\groups\Groups;
use app\widgets\structure\StructureWidget;
use yii\web\View;

$this->title = 'Граф связей';
$this->params['breadcrumbs'][] = ['label' => 'Управление', 'url' => ['/admin']];
$this->params['breadcrumbs'][] = ['label' => 'Команды', 'url' => ['/admin/groups']];
$this->params['breadcrumbs'][] = ['label' => Groups::findModel($id)->name, 'url' => ['/admin/groups/update', 'id' => $id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= StructureWidget::widget([
	'id' => $id
]); ?>


