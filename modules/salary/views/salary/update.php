<?php
declare(strict_types = 1);

/**
 * @var ActiveRecord $model
 * @var View $this
 * @var ArrayDataProvider $userRights
 */

use app\modules\salary\SalaryModule;
use yii\data\ArrayDataProvider;
use yii\db\ActiveRecord;
use yii\web\View;

$this->title = 'Изменить зарплатную вилку';
$this->params['breadcrumbs'][] = SalaryModule::breadcrumbItem('Зарплатные вилки');
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [
	'model' => $model
])
?>