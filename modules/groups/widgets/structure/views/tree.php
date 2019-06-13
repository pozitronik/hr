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
use kartik\switchinput\SwitchInput;
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
				'<div class="col-md-2">
					<label>Физика:'.SwitchInput::widget([
					'name' => 'toggle_physics',
					'containerOptions' => [
						'class' => '',
						'style' => 'float:right'
					],
					'pluginEvents' => [
						"switchChange.bootstrapSwitch" => "function(event, state) { togglePhysics(state); }"
					],
					'pluginOptions' => [

						'size' => 'mini',
						'onText' => 'ДА',
						'offText' => 'НЕТ',
						'onColor' => 'primary',
						'offColor' => 'default'
					]
				]).'</label><label>Иерархия:'.SwitchInput::widget([
					'name' => 'toggle_hierarchy',
					'containerOptions' => [
						'class' => '',
						'style' => 'float:right'
					],
					'pluginEvents' => [
						"switchChange.bootstrapSwitch" => "function(event, state) { toggleHierarchy(state); }"
					],
					'pluginOptions' => [
						'size' => 'mini',
						'onText' => 'ДА',
						'offText' => 'НЕТ',
						'onColor' => 'primary',
						'offColor' => 'default'
					]
				]).'</label></div>
					<div class="col-md-3">
					<label>Выбор нескольких:'.SwitchInput::widget([
					'name' => 'toggle_multiselection',
					'containerOptions' => [
						'class' => '',
						'style' => 'float:right'
					],
					'pluginEvents' => [
						"switchChange.bootstrapSwitch" => "function(event, state) { toggleMultiselection(state); }"
					],
					'pluginOptions' => [

						'size' => 'mini',
						'onText' => 'ДА',
						'offText' => 'НЕТ',
						'onColor' => 'primary',
						'offColor' => 'default'
					]
				]).'</label><label>Автопозиция:'.SwitchInput::widget([
					'value' => true,
					'name' => 'toggle_autofit',
					'containerOptions' => [
						'class' => '',
						'style' => 'float:right'
					],
					'pluginEvents' => [
						"switchChange.bootstrapSwitch" => "function(event, state) { autofit = state }"
					],
					'pluginOptions' => [
						'size' => 'mini',
						'onText' => 'ДА',
						'offText' => 'НЕТ',
						'onColor' => 'primary',
						'offColor' => 'default'
					]
				]).'</label>
					<button class="btn btn-primary" onclick="fitAnimated()" title="Fit"><i class="fa fa-compress-arrows-alt"></i></button>
					</div>'
		]),
		new RibbonPage([
			'caption' => 'Параметры',
			'content' => 'Тут нет ничего'
		])
	]
]) ?>

<div id="tree-container"></div>
