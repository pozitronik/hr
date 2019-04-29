<?php
declare(strict_types = 1);

/**
 * @var DynamicAttributeProperty $model
 * @var DynamicAttributes $attribute
 * @var View $this
 */

use app\modules\dynamic_attributes\DynamicAttributesModule;
use app\modules\dynamic_attributes\models\DynamicAttributeProperty;
use app\modules\dynamic_attributes\models\DynamicAttributes;
use yii\web\View;

$this->title = 'Новое свойство';
$this->params['breadcrumbs'][] = DynamicAttributesModule::breadcrumbItem('Атрибуты');
$this->params['breadcrumbs'][] = DynamicAttributesModule::breadcrumbItem($attribute->name, ['attribute/update', 'id' => $attribute->id]);
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [
	'model' => $model
])
?>