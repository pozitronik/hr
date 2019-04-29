<?php
declare(strict_types = 1);

/**
 * @var ActiveRecord $model
 * @var View $this
 */

use app\modules\vacancy\VacancyModule;
use yii\db\ActiveRecord;
use yii\web\View;

$this->title = 'Создать вакансию';
$this->params['breadcrumbs'][] = VacancyModule::breadcrumbItem('Вакансии');
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form',[
	'model' => $model
])
?>