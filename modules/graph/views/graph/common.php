<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var string $currentConfiguration
 * @var array $positionConfigurations
 */

use app\modules\graph\widgets\position_selector\PositionSelectorWidget;
use app\widgets\ribbon\RibbonPage;
use app\widgets\ribbon\RibbonWidget;
use kartik\switchinput\SwitchInput;
use kartik\touchspin\TouchSpin;
use yii\web\JsExpression;
use yii\web\View;
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
						"switchChange.bootstrapSwitch" => new JsExpression("function(event, state) { graphControl.physics = state; }")
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
						"switchChange.bootstrapSwitch" => new JsExpression("function(event, state) { graphControl.hierarchy = state; }")
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
						"switchChange.bootstrapSwitch" => new JsExpression("function(event, state) { graphControl.multiselection = state; }")
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
						"switchChange.bootstrapSwitch" => new JsExpression("function(event, state) { graphControl.autofit = state }")
					],
					'pluginOptions' => [
						'size' => 'mini',
						'onText' => 'ДА',
						'offText' => 'НЕТ',
						'onColor' => 'primary',
						'offColor' => 'default'
					]
				]).'</label>
					<button class="btn btn-primary" id="fitBtn" title="Fit"><i class="fa fa-compress-arrows-alt"></i></button>
					</div>'.'<div class="col-md-2">'.TouchSpin::widget([
					'name' => 'upDepth',
					'pluginOptions' => [
						'verticalbuttons' => true,
						'min' => -1,
//						'initval' => -1
					],
					'options' => [
						'placeholder' => 'Детализация вверх'
					],
					'pluginEvents' => [
						"change" => new JsExpression('function(event) { graphControl.upDepth = this.value}')
					]

				]).'</div><div class="col-md-2">'.TouchSpin::widget([
					'name' => 'downDepth',
					'pluginOptions' => [
						'verticalbuttons' => true,
						'min' => -1,
//						'initval' => -1
					],
					'options' => [
						'placeholder' => 'Детализация вниз'
					],
					'pluginEvents' => [
						"change" => new JsExpression('function(event) { graphControl.downDepth = this.value}')
					]
				]).'</div>'
		]),
		new RibbonPage([
			'caption' => 'Фильтры',
			'content' => ''
		]),
		new RibbonPage([
			'caption' => 'Редактор',
			'content' => 'Тут нет ничего'
		])
	]
]) ?>

<div id="tree-container"></div>
