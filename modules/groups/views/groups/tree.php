<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var integer $id
 */

use app\models\core\core_module\CoreModule;
use app\modules\groups\models\Groups;
use app\modules\groups\widgets\structure\StructureWidget;
use yii\web\View;

$this->title = 'Граф связей: '.Groups::findModel($id)->name;

$this->params['breadcrumbs'][] = CoreModule::breadcrumbItem('Группы');
$this->params['breadcrumbs'][] = CoreModule::breadcrumbItem(Groups::findModel($id)->name, 'groups/profile', ['id' => $id]);

$this->params['breadcrumbs'][] = $this->title;
?>

<?= StructureWidget::widget([
	'id' => $id
]); ?>


