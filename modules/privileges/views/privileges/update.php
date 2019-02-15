<?php
declare(strict_types = 1);

/**
 * @var ActiveRecord $model
 * @var View $this
 * @var ArrayDataProvider $userRights
 */

use yii\data\ArrayDataProvider;
use yii\db\ActiveRecord;
use yii\web\View;

$this->title = 'Изменить привилегию';
$this->params['breadcrumbs'][] = ['label' => 'Управление', 'url' => ['/admin']];
$this->params['breadcrumbs'][] = ['label' => 'Привилегии', 'url' => ['/admin/privileges']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', compact('model', 'userRights'));
?>