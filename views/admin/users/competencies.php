<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Users $user
 * @var Competencies $competency
 */

use app\models\competencies\Competencies;
use app\models\competencies\CompetencyField;
use app\models\users\Users;
use kartik\date\DatePicker;
use kartik\range\RangeInput;
use kartik\switchinput\SwitchInput;
use kartik\time\TimePicker;
use yii\web\View;
use kartik\grid\GridView;
use yii\data\ArrayDataProvider;
use kartik\form\ActiveForm;
use yii\helpers\Html;

$dataProvider = new ArrayDataProvider([//todo move to controller
	'allModels' => $competency->getUserFields($user->id)
]);

$this->title = "{$user->username}: {$competency->name} ";
$this->params['breadcrumbs'][] = ['label' => 'Управление', 'url' => ['/admin']];
$this->params['breadcrumbs'][] = ['label' => 'Люди', 'url' => ['/admin/users']];
$this->params['breadcrumbs'][] = ['label' => $user->username, 'url' => ['/admin/users/update', 'id' => $user->id]];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="panel panel-primary">
	<?php $form = ActiveForm::begin(); ?>
	<div class="panel-heading">
		<div class="panel-control">
		</div>
		<h3 class="panel-title"><?= Html::encode($this->title); ?></h3>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-xs-12">
				<?= GridView::widget([
					'dataProvider' => $dataProvider,
					'showFooter' => false,
					'showPageSummary' => false,
					'summary' => '',
					'panel' => false,
					'toolbar' => false,
					'export' => false,
					'resizableColumns' => true,
					'responsive' => true,
					'columns' => [
						'name',
						[
							'format' => 'raw',
							'attribute' => 'value',
							'label' => 'Значение',
							'value' => function($model) use ($form) {
								/** @var CompetencyField $model */

								switch ($model->type) {
									case 'boolean':
										return $form->field($model, (string)$model->id)->widget(SwitchInput::class)->label(false);
									break;
									case 'date':
										return $form->field($model, (string)$model->id)->widget(DatePicker::class, [
											'pluginOptions' => [
												'autoclose' => true,
												'format' => 'yyyy-mm-dd'
											]
										])->label(false);
									break;
									case 'integer':
										return $form->field($model, (string)$model->id)->textInput(['type' => 'number'])->label(false);
									break;
									case 'percent':
										return $form->field($model, (string)$model->id)->widget(RangeInput::class, [
											'html5Options' => [
												'min' => 0,
												'max' => 100
											],
											'html5Container' => [
												'style' => 'width:50%'
											],
											'addon' => [
												'append' => [
													'content' => '%'
												],
												'prepend' => [
													'content' => '<span class="text-danger">0%</span>'
												],
												'preCaption' => '<span class="input-group-addon"><span class="text-success">100%</span></span>'
											]
										])->label(false);
									break;
									case 'string':
										return $form->field($model, (string)$model->id)->textarea()->label(false);
									break;
									case 'time':
										return $form->field($model, (string)$model->id)->widget(TimePicker::class, [
											'pluginOptions' => [
												'showSeconds' => true,
												'showMeridian' => false,
												'minuteStep' => 1,
												'secondStep' => 5
											]
										])->label(false);
									break;
									default:
										return $form->field($model, (string)$model->id)->textInput()->label(false);
									break;

								}
							}
						]
					]
				]); ?>


			</div>
		</div>
	</div>
	<div class="panel-footer">
		<div class="btn-group">
			<?= Html::submitButton($competency->isNewRecord?'Сохранить':'Изменить', ['class' => $competency->isNewRecord?'btn btn-success':'btn btn-primary']); ?>
			<?php if ($competency->isNewRecord): ?>
				<?= Html::input('submit', 'more', 'Сохранить и добавить ещё', ['class' => 'btn btn-primary']); ?>
			<?php endif ?>
		</div>
	</div>
	<?php ActiveForm::end(); ?>
</div>




