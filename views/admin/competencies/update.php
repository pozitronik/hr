<?php
declare(strict_types = 1);

/**
 * @var Competencies $model
 * @var View $this
 */

use app\models\competencies\Competencies;
use yii\web\View;

$this->title = 'Редактирование компетеции '.$model->name;
$this->params['breadcrumbs'][] = ['label' => 'Управление', 'url' => ['/admin']];
$this->params['breadcrumbs'][] = ['label' => 'Компетенции', 'url' => ['/admin/competencies']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [
	'model' => $model
]);
?>