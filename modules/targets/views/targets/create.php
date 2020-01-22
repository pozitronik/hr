<?php
declare(strict_types = 1);

/**
 * @var ActiveRecord $model
 * @var View $this
 */

use app\modules\targets\TargetsModule;
use yii\db\ActiveRecord;
use yii\web\View;

$this->title = 'Создать задачу целеполагания';
$this->params['breadcrumbs'][] = TargetsModule::breadcrumbItem('Целеполагание');
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form',[
	'model' => $model
])
?>