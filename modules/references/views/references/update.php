<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Reference $model
 */

use app\models\core\core_module\CoreModule;
use app\modules\references\models\Reference;
use yii\web\View;

$this->title = "Изменить запись в справочнике ".$model->menuCaption;
$this->params['breadcrumbs'][] = CoreModule::breadcrumbItem('Справочники');
$this->params['breadcrumbs'][] = CoreModule::breadcrumbItem($model->menuCaption, 'references/index', ['class' => $model->formName()]);
$this->params['breadcrumbs'][] = $this->title;
?>
<?= $this->render($model->form, [
	'model' => $model
]) ?>