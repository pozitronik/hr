<?php
declare(strict_types = 1);

/**
 * @var ActiveRecord $model
 * @var View $this
 * @var ArrayDataProvider $userRights
 */

use app\models\core\core_module\CoreModule;
use yii\data\ArrayDataProvider;
use yii\db\ActiveRecord;
use yii\web\View;

$this->title = 'Создать привилегию';
$this->params['breadcrumbs'][] = CoreModule::breadcrumbItem('Привилегии');
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', compact('model', 'userRights'))
?>