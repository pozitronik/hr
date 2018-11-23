<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var PrototypeCompetenciesSearchSet $model
 */

use app\assets\AppAsset;
use app\models\prototypes\PrototypeCompetenciesSearchSet;
use yii\web\View;
use kartik\form\ActiveForm;
use yii\helpers\Html;
use kartik\switchinput\SwitchInput;
use kartik\select2\Select2;
use app\helpers\ArrayHelper;
use app\models\competencies\Competencies;

$this->title = 'Поиск';
$this->params['breadcrumbs'][] = ['label' => 'Управление', 'url' => ['/admin']];
$this->params['breadcrumbs'][] = ['label' => 'Компетенции', 'url' => ['/admin/competencies']];
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsFile('js/competency_search.js', ['depends' => AppAsset::class]);//todo: после прототипирования вытащить в виджет или модуль
?>
<div class="row">
	<div class="col-xs-12">
		<?php $form = ActiveForm::begin(); ?>
		<div class="panel panel-primary">
			<div class="panel-heading">
				<div class="panel-control">
					<?= Html::button("<i class='glyphicon glyphicon-minus'></i>", ['class' => 'btn btn-danger', 'onclick' => 'removeCondition()']); ?>
					<?= Html::button("<i class='glyphicon glyphicon-plus'></i>", ['class' => 'btn btn-success', 'type' => 'submit', 'name' => 'add', 'value' => true]); ?>
				</div>
				<h3 class="panel-title"><?= Html::encode($this->title); ?></h3>
			</div>

			<div class="panel-body">
				<?php foreach ($model->set as $index => $condition): ?>
					<div class="row" data-index='<?= $index ?>'>
						<div class="col-md-1">
							<?= $form->field($condition, "logic[$index]")->widget(SwitchInput::class, [
								'options' => [
									'name' => "{$model->formName()}[$index][logic]"
								],
								'pluginOptions' => [
									'size' => 'mini',
									'onText' => 'И',
									'offText' => 'ИЛИ',
									'onColor' => 'primary',
									'offColor' => 'primary'
								]
							]); ?>
						</div>
						<div class="col-md-3">
							<?= $form->field($condition, "competency[$index]")->widget(Select2::class, [
								/*todo: группировка по категориям*/
								'data' => ArrayHelper::cmap(Competencies::find()->active()->all(), 'id', ['name', 'categoryName'], ' => '),
								'options' => [
									'name' => "{$model->formName()}[$index][competency]",
									'multiple' => false,
									'placeholder' => 'Выбрать компетенцию',
									'data-competency' => $index,
									'onchange' => 'competency_changed($(this))'
								]
							]); ?>
						</div>
						<div class="col-md-3">
							<?= $form->field($condition, "field[$index]")->widget(Select2::class, [
								'data' => $model->competencyFields($model->set[$index]->competency),
								'options' => [
									'name' => "{$model->formName()}[$index][field]",
									'multiple' => false,
									'placeholder' => 'Выбрать поле',
									'data-field' => $index,
									'onchange' => 'field_changed($(this))'
								]
							]); ?>
						</div>
						<div class="col-md-2">
							<?= $form->field($condition, "condition[$index]")->widget(Select2::class, [
								'data' => $model->fieldsConditions($model->set[$index]->competency, $model->set[$index]->field),
								'options' => [
									'name' => "{$model->formName()}[$index][condition]",
									'multiple' => false,
									'placeholder' => 'Выбрать условие',
									'data-condition' => $index,
								]
							]); ?>
						</div>
						<div class="col-md-3">
							<?= $form->field($model, "value[$index]")->textInput([
								'name' => "{$model->formName()}[$index][value]"
							]); ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>

			<div class="panel-footer">
				<div class="btn-group">
					<?= Html::submitButton('Поиск', ['class' => 'btn btn-primary']); ?>
				</div>
				<div class="btn-group pull-right">
					<?= Html::button('Сохранить поиск', ['class' => 'btn btn-info']); ?>
				</div>
			</div>
		</div>
		<?php ActiveForm::end(); ?>
	</div>
</div>

