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
				<h3 class="panel-title pull-left">
					<?= $form->field($model, 'logic_mode_and', [
						'options' => [
							'style' => 'width:500px;'
						]
					])->widget(SwitchInput::class)->label(null, ['class' => 'pull-left']); ?>
				</h3>

			</div>

			<div class="panel-body">
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

