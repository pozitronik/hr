<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var PrototypeCompetenciesSearch $model
 */

use app\models\prototypes\PrototypeCompetenciesSearch;
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

?>
<div class="row">
	<div class="col-xs-12">
		<?php $form = ActiveForm::begin(); ?>
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title"><?= Html::encode($this->title); ?></h3>
			</div>

			<div class="panel-body">
				<div class="row">
					<div class="col-md-1">
						<?= $form->field($model, 'logic')->widget(SwitchInput::class, [
							'pluginOptions' => [
								'size' => 'mini',
								'onText' => 'И',
								'offText' => 'ИЛИ',
								'onColor' => 'primary',
								'offColor' => 'primary',
							]
						]); ?>
					</div>
					<div class="col-md-3">
						<?= $form->field($model, 'competency')->widget(Select2::class, [
							'data' => ArrayHelper::map(Competencies::find()->active()->all(), 'id', 'name'),
							'options' => [
								'multiple' => false,
								'placeholder' => 'Выбрать компетенцию'
							]
						]); ?>
					</div>
					<div class="col-md-3">
						<?= $form->field($model, 'field')->widget(Select2::class, [
							'data' => [],
							'options' => [
								'multiple' => false,
								'placeholder' => 'Выбрать поле'
							]
						]); ?>
					</div>
					<div class="col-md-2">
						<?= $form->field($model, 'condition')->widget(Select2::class, [
							'data' => [],
							'options' => [
								'multiple' => false,
								'placeholder' => 'Выбрать условие'
							]
						]); ?>
					</div>
					<div class="col-md-3">
						<?= $form->field($model, 'value'); ?>
					</div>
				</div>
			</div>

			<div class="panel-footer">
				<div class="btn-group">
					<?= Html::submitButton('Поиск', ['class' => 'btn btn-primary']); ?>
				</div>
				<div class="btn-group  pull-right">
					<?= Html::button('Сохранить поиск', ['class' => 'btn btn-info']); ?>
				</div>
			</div>
		</div>
		<?php ActiveForm::end(); ?>
	</div>
</div>

