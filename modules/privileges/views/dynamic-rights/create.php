<?php
declare(strict_types = 1);

/**
 * @var ActiveRecord $model
 * @var View $this
 */

use app\models\core\core_module\CoreModule;
use yii\db\ActiveRecord;
use yii\web\View;

$this->title = 'Создать динамическое правило';
$this->params['breadcrumbs'][] = CoreModule::breadcrumbItem('Динамические правила');
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', compact('model'))
?>