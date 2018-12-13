<?php
declare(strict_types = 1);

/**
 * @var Users $model
 * @var View $this
 * @var array $attributesData
 */

use app\models\users\Users;
use yii\web\View;

$this->title = 'Добавление пользователя';
$this->params['breadcrumbs'][] = ['label' => 'Управление', 'url' => ['/admin']];
$this->params['breadcrumbs'][] = ['label' => 'Люди', 'url' => ['/admin/users']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', compact('model', 'attributesData'));
?>