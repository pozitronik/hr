<?php
declare(strict_types = 1);

/**
 * @var ActiveRecord $model
 * @var View $this
 * @var ArrayDataProvider $userRights
 */

use app\modules\vacancy\VacancyModule;
use yii\data\ArrayDataProvider;
use yii\db\ActiveRecord;
use yii\web\View;

$this->title = 'Изменить вакансию';
$this->params['breadcrumbs'][] = VacancyModule::breadcrumbItem('Вакансии');
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [
	'model' => $model
])
?>