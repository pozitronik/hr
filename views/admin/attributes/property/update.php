<?php
declare(strict_types = 1);

/**
 * @var DynamicAttributeProperty $model
 * @var DynamicAttributes $attribute
 * @var View $this
 */

use app\models\dynamic_attributes\DynamicAttributes;
use app\models\dynamic_attributes\DynamicAttributeProperty;
use yii\web\View;

$this->title = "Редактирование свойства {$model->name}";
$this->params['breadcrumbs'][] = ['label' => 'Управление', 'url' => ['/admin']];
$this->params['breadcrumbs'][] = ['label' => 'Атрибуты', 'url' => ['/admin/attributes']];
$this->params['breadcrumbs'][] = ['label' => $attribute->name, 'url' => ['/admin/attributes/update', 'id' => $attribute->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [
	'model' => $model
]);
?>