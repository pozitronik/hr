<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var int|null $id
 * @var string $currentConfiguration
 * @var array $positionConfigurations
 */

use app\modules\graph\assets\VisjsAssetTargets;
use app\modules\targets\models\Targets;
use app\modules\targets\TargetsModule;
use yii\web\View;

$this->title = 'Дерево структуры: '.Targets::findModel($id)->name;

$this->params['breadcrumbs'][] = TargetsModule::breadcrumbItem('Целеполагание');

$this->params['breadcrumbs'][] = $this->title;
VisjsAssetTargets::register($this);

$this->registerJs("graphControl = new GraphControl(_.$('tree-container'), $id,'-1','-1'); $('#fitBtn').on('click',function() {graphControl.fitAnimated()})", View::POS_END);
?>

<?= $this->render('common', compact('currentConfiguration', 'positionConfigurations')) ?>
