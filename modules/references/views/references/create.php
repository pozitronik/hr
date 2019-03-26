<?php
declare(strict_types = 1);

use app\models\core\core_module\CoreModule;
use yii\web\View;
use app\modules\references\models\Reference;

/**
 * @var View $this
 * @var Reference $model
 */

$this->title = "Новая запись в справочнике ".$model->menuCaption;
$this->params['breadcrumbs'][] = CoreModule::breadcrumbItem('Справочники');
$this->params['breadcrumbs'][] = CoreModule::breadcrumbItem($model->menuCaption, 'references/index', ['class' => $model->formName()]);
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render($model->form, [
	'model' => $model
]) ?>