<?php
declare(strict_types = 1);

/**
 * @var ActiveRecord $model
 * @var View $this
 */

use app\modules\salary\SalaryModule;
use yii\db\ActiveRecord;
use yii\web\View;

$this->title = 'Создать зарплатную вилку';
$this->params['breadcrumbs'][] = SalaryModule::breadcrumbItem('Зарплатные вилки');
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form',[
	'model' => $model
])
?>