<?php
declare(strict_types = 1);

/**
 * @var Groups $model
 * @var View $this
 */

use app\modules\groups\models\Groups;
use yii\web\View;

$this->title = 'Создать группу';
$this->params['breadcrumbs'][] = ['label' => 'Управление', 'url' => ['/admin']];
$this->params['breadcrumbs'][] = ['label' => 'Группы', 'url' => ['/groups/groups']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [
	'model' => $model
]);
?>