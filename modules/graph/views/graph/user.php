<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var int|null $id
 * @var string $currentConfiguration
 * @var array $positionConfigurations
 */

use app\modules\graph\VisjsAsset;
use app\modules\users\models\Users;
use app\modules\users\UsersModule;
use yii\web\View;

$this->title = 'Дерево структуры: '.Users::findModel($id)->username;

$this->params['breadcrumbs'][] = UsersModule::breadcrumbItem('Пользователи');
$this->params['breadcrumbs'][] = UsersModule::breadcrumbItem(Users::findModel($id)->username, ['users/profile', 'id' => $id]);

$this->params['breadcrumbs'][] = $this->title;
VisjsAsset::register($this);

$this->registerJs("graphControl = new GraphControl(_.$('tree-container'), '-1', $id); $('#fitBtn').on('click',function() {graphControl.fitAnimated()})", View::POS_END);
?>

<?= $this->render('common', compact('currentConfiguration','positionConfigurations')) ?>
