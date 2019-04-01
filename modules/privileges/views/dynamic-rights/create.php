<?php
declare(strict_types = 1);

/**
 * @var ActiveRecord $model
 * @var View $this
 */

use app\models\core\core_module\CoreModule;
use yii\db\ActiveRecord;
use yii\web\View;

$this->title = 'Создать правило';
$this->params['breadcrumbs'][] = CoreModule::breadcrumbItem('Привилегии');
$this->params['breadcrumbs'][] = CoreModule::breadcrumbItem('Правила доступа', 'dynamic-rights/index');
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', compact('model'))
?>