<?php
declare(strict_types = 1);

/**
 * @var Competencies $competency
 * @var View $this
 */

use app\models\competencies\Competencies;
use yii\web\View;

$this->title = 'Новое поле';
$this->params['breadcrumbs'][] = ['label' => 'Управление', 'url' => ['/admin']];
$this->params['breadcrumbs'][] = ['label' => 'Компетенции', 'url' => ['/admin/competencies']];
$this->params['breadcrumbs'][] = ['label' => $competency->name, 'url' => ['/admin/competencies/update', 'id' => $competency->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form');
?>