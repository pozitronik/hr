<?php
declare(strict_types = 1);

/**
 * @var DynamicAttributeProperty $model
 * @var DynamicAttributes $attribute
 * @var View $this
 */

use app\models\core\core_module\CoreModule;
use app\modules\dynamic_attributes\models\DynamicAttributes;
use app\modules\dynamic_attributes\models\DynamicAttributeProperty;
use yii\web\View;

$this->title = "Редактирование свойства {$model->name}";
$this->params['breadcrumbs'][] = CoreModule::breadcrumbItem('Атрибуты');
$this->params['breadcrumbs'][] = CoreModule::breadcrumbItem($attribute->name, 'attribute/update', ['id' => $attribute->id]);
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [
	'model' => $model
]);
?>