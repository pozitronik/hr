<?php
declare(strict_types = 1);

/**
 * @var DynamicAttributes $model
 * @var View $this
 */

use app\models\dynamic_attributes\DynamicAttributes;
use yii\web\View;

$this->title = 'Новый атрибут';
$this->params['breadcrumbs'][] = ['label' => 'Управление', 'url' => ['/admin']];
$this->params['breadcrumbs'][] = ['label' => 'Атрибуты', 'url' => ['/admin/attributes']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?=
$this->render('_form', [
	'model' => $model
]);
?>