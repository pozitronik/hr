<?php
declare(strict_types = 1);

/**
 * @var Users $user
 * @var Competencies $competency
 * @var View $this
 */

use app\models\competencies\Competencies;
use app\models\users\Users;
use yii\web\View;

$this->title = 'Редактирование компетенции';
$this->params['breadcrumbs'][] = ['label' => 'Управление', 'url' => ['/admin']];
$this->params['breadcrumbs'][] = ['label' => 'Люди', 'url' => ['/admin/users']];
$this->params['breadcrumbs'][] = ['label' => 'Профиль пользователя '.$user->username, 'url' => ['/admin/users/update', 'id' => $user->id]];
$this->params['breadcrumbs'][] = ['label' => 'Компетенции', 'url' => ['/admin/users/competency', 'id' => $user->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', compact('user', 'competency'));
?>