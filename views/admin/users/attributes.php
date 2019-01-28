<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Users $user
 * @var DynamicAttributes $attribute
 */

use app\models\dynamic_attributes\DynamicAttributes;
use app\models\dynamic_attributes\DynamicAttributeProperty;
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

$this->title = "{$user->username}: {$attribute->name} ";
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
					'dataProvider' => new ArrayDataProvider([
						'allModels' => $attribute->getUserProperties($user->id)
					]),
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
								/** @var DynamicAttributeProperty $model */

								switch ($model->type) {
									case 'boolean':
										return $form->field($model, (string)$model->id)->widget(SwitchInput::class)->label(false);
									break;
									case 'date':
										return $form->field($model, (string)$model->id)->widget(DatePicker::class, [
											'pluginOptions' => [
												'autoclose' => true,
												'format' => 'yyyy-mm-dd'
											],
											'options' => [
												'placeholder' => 'Укажите дату'
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
											],
											'options' => [
												'placeholder' => 'Укажите значение'
											]
										])->label(false);
									break;
									case 'score':
										return GridView::widget([
											'dataProvider' => new ArrayDataProvider([
												'allModels' => $model->value,
											]),
											'panel' => false,
											'summary' => "Редактор не подвезли пока",
											'headerRowOptions' => [
												'style' => 'display:none'
											],
											'toolbar' => false,
											'export' => false,
											'resizableColumns' => false,
											'responsive' => true,
											'options' => [
												'class' => 'attribute_table'
											],
										]);
//										return $form->field($model, (string)$model->id)->widget(RangeInput::class, [
//											'html5Options' => [
//												'min' => 0,
//												'max' => 5
//											],
//											'html5Container' => [
//												'style' => 'width:50%'
//											],
//											'options' => [
//												'placeholder' => 'Укажите значение'
//											]
//										])->label(false);
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
												'secondStep' => 5,
												'defaultTime' => false
											],
											'options' => [
												'placeholder' => 'Укажите время'
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
			<?= Html::submitButton($attribute->isNewRecord?'Сохранить':'Изменить', ['class' => $attribute->isNewRecord?'btn btn-success':'btn btn-primary']); ?>
			<?php if ($attribute->isNewRecord): ?>
				<?= Html::input('submit', 'more', 'Сохранить и добавить ещё', ['class' => 'btn btn-primary']); ?>
			<?php endif ?>
		</div>
	</div>
	<?php ActiveForm::end(); ?>
</div>




