<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var int|null $id
 * @var string $currentConfiguration
 * @var array $positionConfigurations
 */

use app\modules\graph\VisjsAsset;
use app\modules\groups\GroupsModule;
use app\modules\groups\models\Groups;
use yii\web\View;

$this->title = 'Дерево структуры: '.Groups::findModel($id)->name;

$this->params['breadcrumbs'][] = GroupsModule::breadcrumbItem('Группы');
$this->params['breadcrumbs'][] = GroupsModule::breadcrumbItem(Groups::findModel($id)->name, ['groups/profile', 'id' => $id]);

$this->params['breadcrumbs'][] = $this->title;
VisjsAsset::register($this);

$this->registerJs("graphControl = new GraphControl(_.$('tree-container'), $id, -1, -1, -1); $('#fitBtn').on('click',function() {graphControl.fitAnimated()})", View::POS_END);
?>
<?= $this->render('common', compact('currentConfiguration', 'positionConfigurations')) ?>