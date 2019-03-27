<?php
declare(strict_types = 1);

/**
 * @var ActiveRecord $model
 * @var View $this
 * @var array $rules
 */

use app\models\core\core_module\CoreModule;
use yii\db\ActiveRecord;
use yii\web\View;

$this->title = 'Изменить динамическое правило';
$this->params['breadcrumbs'][] = CoreModule::breadcrumbItem('Динамические правила');
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', compact('model', 'rules'))
?>