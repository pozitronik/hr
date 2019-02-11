<?php
declare(strict_types = 1);

/**
 * @var Groups $model
 * @var View $this
 */

use yii\web\View;
use app\modules\groups\models\Groups;

$this->title = 'Изменить группу '.$model->name;
$this->params['breadcrumbs'][] = ['label' => 'Управление', 'url' => ['/admin']];
$this->params['breadcrumbs'][] = ['label' => 'Группы', 'url' => ['/groups/groups']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [
	'model' => $model
]);
?>