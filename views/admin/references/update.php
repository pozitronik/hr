<?php /** @noinspection PhpRedundantClosingTagInspection */
declare(strict_types = 1);
/**
 * @var View $this
 * @var Reference $model
 */

use app\models\references\Reference;
use yii\web\View;

$this->title = "Изменить запись в справочнике ".$model->menuCaption;
$this->params['breadcrumbs'][] = ['label' => 'Управление', 'url' => ['/admin']];
$this->params['breadcrumbs'][] = ['label' => 'Справочники', 'url' => ['/admin/references']];
$this->params['breadcrumbs'][] = ['label' => $model->menuCaption, 'url' => ['/admin/references/index', 'class' => $model->classNameShort]];
$this->params['breadcrumbs'][] = $this->title;
?>
<?= $this->render($model->form, [
	'model' => $model
]);