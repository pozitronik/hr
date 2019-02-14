<?php /** @noinspection PhpRedundantClosingTagInspection */
declare(strict_types = 1);

use yii\web\View;
use app\modules\references\models\Reference;

/**
 * @var View $this
 * @var Reference $model
 */

$this->title = "Новая запись в справочнике ".$model->menuCaption;
$this->params['breadcrumbs'][] = ['label' => 'Управление', 'url' => ['/admin']];
$this->params['breadcrumbs'][] = ['label' => 'Справочники', 'url' => ['/references/references']];
$this->params['breadcrumbs'][] = ['label' => $model->menuCaption, 'url' => ['/references/references/index', 'class' => $model->formName()]];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render($model->form, [
	'model' => $model
]);