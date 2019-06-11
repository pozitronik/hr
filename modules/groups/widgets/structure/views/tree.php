<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var integer $id
 * @var string $currentConfiguration
 * @var array $positionConfigurations
 */

use app\modules\groups\widgets\graph_widgets\position_selector\PositionSelectorWidget;
use app\widgets\ribbon\RibbonPage;
use app\widgets\ribbon\RibbonWidget;
use yii\web\View;

$this->registerJs("init_tree($id);", View::POS_END);
?>

<?= RibbonWidget::widget([
	'options' => [
		'id' => 'controls-block'
	],
	'pages' => [
		new RibbonPage([
			'active' => true,
			'expanded' => true,
			'caption' => 'Позиция',
			'content' => '<div class="col-md-2">'.PositionSelectorWidget::widget(compact('currentConfiguration', 'positionConfigurations')).'</div>'.
				'<div class="col-md-3">
					<button class="btn btn-primary" onclick="fitAnimated()">fit</button>
					<button class="btn btn-primary" onclick="togglePhysics()">ФИЗИКА</button>
				</div>'
		]),
		new RibbonPage([
			'caption' => 'Параметры',
			'content' => 'pupupupu'
		])
	]
]) ?>

<div id="tree-container"></div>
