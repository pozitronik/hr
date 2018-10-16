<?php
/**
 * @var yii\web\View $this
 * @var app\models\references\Reference $model
 */

$this->title = 'Изменить запись: '.' '.$model->title;
$this->params['breadcrumbs'][] = ['label' => 'Админка', 'url' => ['admin/admin']];
$this->params['breadcrumbs'][] = ['label' => 'Справочники', 'url' => ['admin/references']];
$this->params['breadcrumbs'][] = ['label' => $model->ref_name, 'url' => ['view', 'id' => $model->id, 'class' => $model->classNameShort]];
$this->params['breadcrumbs'][] = 'Изменить';

echo $this->render($model->form, [
	'model' => $model
]);