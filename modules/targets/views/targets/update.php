<?php
declare(strict_types = 1);

/**
 * @var Targets $model
 * @var View $this
 * @var ArrayDataProvider $userRights
 */

use app\modules\targets\models\Targets;
use app\modules\targets\TargetsModule;
use yii\data\ArrayDataProvider;
use yii\web\View;

$this->title = "Изменить задание целеполагания";
$this->params['breadcrumbs'][] = TargetsModule::breadcrumbItem('Целеполагание');
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render($model->isFinal?'final_form':'_form', [
	'model' => $model
])
?>