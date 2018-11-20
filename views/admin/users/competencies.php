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
use kartik\slider\Slider;
use kartik\switchinput\SwitchInput;
use kartik\time\TimePicker;
use yii\web\View;
use kartik\grid\GridView;
use yii\data\ArrayDataProvider;
use kartik\form\ActiveForm;
use yii\helpers\Html;

$dataProvider = new ArrayDataProvider([
	'allModels' => $competency->getUserFields($user->id)
]);

?>
<div class="row">
	<div class="col-xs-12">
		<?php $form = ActiveForm::begin(); ?>
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
					'value' => function($model) use ($form) {
						/** @var CompetencyField $model */

						switch ($model->type) {
							case 'boolean':
								return $form->field($model, (string)$model->id)->widget(SwitchInput::class, [
									'value' => null === $model->value?'':$model->value
								]);
							break;
							case 'date':
								return $form->field($model, (string)$model->id)->widget(DatePicker::class, [
									'value' => null === $model->value?'':$model->value
								]);
							break;
							case 'integer':
								return $form->field($model, (string)$model->id)->textInput([
									'value' => null === $model->value?'':$model->value
								]);
							break;
							case 'percent':
								return $form->field($model, (string)$model->id)->widget(Slider::class, [
									'value' => null === $model->value?'':$model->value
								]);
							break;
							case 'range':
								return $form->field($model, (string)$model->id)->widget(RangeInput::class, [
									'value' => null === $model->value?'':$model->value
								]);
							break;
							case 'string':
								return $form->field($model, (string)$model->id)->textarea([
									'value' => null === $model->value?'':$model->value
								]);
							break;
							case 'time':
								return $form->field($model, (string)$model->id)->widget(TimePicker::class, [
									'value' => null === $model->value?'':$model->value
								]);
							break;
							default:
								return $form->field($model, (string)$model->id)->textInput([
									'value' => null === $model->value?'':$model->value
								])->label(false);
							break;

						}
					}
				]
			]
		]); ?>

		<div class="btn-group">
			<?= Html::submitButton($competency->isNewRecord?'Сохранить':'Изменить', ['class' => $competency->isNewRecord?'btn btn-success':'btn btn-primary']); ?>
			<?php if ($competency->isNewRecord): ?>
				<?= Html::input('submit', 'more', 'Сохранить и добавить ещё', ['class' => 'btn btn-primary']); ?>
			<?php endif ?>
		</div>
		<?php ActiveForm::end(); ?>
	</div>
</div>

