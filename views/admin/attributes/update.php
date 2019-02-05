<?php
declare(strict_types = 1);

/**
 * @var DynamicAttributes $model
 * @var View $this
 */

use app\modules\dynamic_attributes\models\DynamicAttributes;
use yii\web\View;

$this->title = 'Редактирование атрибута '.$model->name;
$this->params['breadcrumbs'][] = ['label' => 'Управление', 'url' => ['/admin']];
$this->params['breadcrumbs'][] = ['label' => 'Атрибуты', 'url' => ['/admin/attributes']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [
	'model' => $model
]);
?>