<?php
declare(strict_types = 1);

/**
 * @var DynamicAttributes $model
 * @var View $this
 */

use app\modules\dynamic_attributes\DynamicAttributesModule;
use app\modules\dynamic_attributes\models\DynamicAttributes;
use yii\web\View;

$this->title = 'Редактирование атрибута '.$model->name;
$this->params['breadcrumbs'][] = DynamicAttributesModule::breadcrumbItem('Атрибуты');
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [
	'model' => $model
])
?>