<?php
declare(strict_types = 1);

/**
 * @var ActiveRecord $model
 * @var View $this
 */

use app\modules\privileges\PrivilegesModule;
use yii\db\ActiveRecord;
use yii\web\View;

$this->title = 'Изменить правило';
$this->params['breadcrumbs'][] = PrivilegesModule::breadcrumbItem('Привилегии');
$this->params['breadcrumbs'][] = PrivilegesModule::breadcrumbItem('Правила доступа', 'dynamic-rights/index');
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', compact('model'))
?>