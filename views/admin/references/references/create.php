<?php

use yii\web\View;
use app\models\references\Reference;

/**
 * @var View $this
 * @var Reference $model
 */

$this->title = 'Создать запись';
$this->params['breadcrumbs'][] = ['label' => 'Админка', 'url' => ['admin/admin']];
$this->params['breadcrumbs'][] = ['label' => 'Справочники', 'url' => ['admin/references']];
$this->params['breadcrumbs'][] = ['label' => $model->ref_name, 'url' => ['index', 'class' => $model->classNameShort]];
$this->params['breadcrumbs'][] = $this->title;


echo $this->render($model->form, [
	'model' => $model
]);