<?php
declare(strict_types = 1);

use app\modules\references\ReferencesModule;
use yii\web\View;
use app\modules\references\models\Reference;

/**
 * @var View $this
 * @var Reference $model
 */

$this->title = "Новая запись в справочнике ".$model->menuCaption;
$this->params['breadcrumbs'][] = ReferencesModule::breadcrumbItem('Справочники');
$this->params['breadcrumbs'][] = ReferencesModule::breadcrumbItem($model->menuCaption, ['references/index', 'class' => $model->formName()]);
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render($model->form, [
	'model' => $model
]) ?>