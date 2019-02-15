<?php
declare(strict_types = 1);

/**
 * @var DynamicAttributes $model
 * @var View $this
 */

use app\models\core\core_module\CoreModule;
use app\modules\dynamic_attributes\models\DynamicAttributes;
use yii\web\View;

$this->title = 'Новый атрибут';
$this->params['breadcrumbs'][] = CoreModule::breadcrumbItem('Атрибуты');
$this->params['breadcrumbs'][] = $this->title;
?>

<?=
$this->render('_form', [
	'model' => $model
]);
?>