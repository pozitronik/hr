<?php
declare(strict_types = 1);

/**
 * @var CompetencyField $model
 * @var Competencies $competency
 * @var View $this
 */

use app\models\competencies\Competencies;
use app\models\competencies\CompetencyField;
use yii\web\View;

$this->title = "Редактирование поля {$model->name} компетенции { $competency->name}";
$this->params['breadcrumbs'][] = ['label' => 'Управление', 'url' => ['/admin']];
$this->params['breadcrumbs'][] = ['label' => 'Компетенции', 'url' => ['/admin/competencies']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form' /*[
//	'model' => $model todo
]*/);
?>