<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Groups $model
 * @var DynamicAttributes[] $aggregatedAttributes
 * @var DynamicModel $parametersModel
 */

use app\modules\dynamic_attributes\models\DynamicAttributePropertyAggregation;
use app\modules\dynamic_attributes\models\DynamicAttributes;
use app\modules\dynamic_attributes\widgets\dynamic_attribute\DynamicAttributeWidget;
use app\modules\groups\models\Groups;
use kartik\form\ActiveForm;
use kartik\helpers\Html;
use kartik\select2\Select2;
use kartik\switchinput\SwitchInput;
use yii\base\DynamicModel;
use yii\bootstrap\Button;
use yii\web\View;

$this->title = 'Статистика по атрибутам';
$this->params['breadcrumbs'][] = $this->title;

?>

<?php $form = ActiveForm::begin(); ?>
	<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
	</div>

	<div class="panel-body">
		<div class="row">
			<div class="col-xs-12">
				<div class="col-md-9">

					<?= $form->field($parametersModel, 'aggregation')->widget(Select2::class, [
						'data' => DynamicAttributePropertyAggregation::AGGREGATION_LABELS,
						'options' => [
							'multiple' => false,
							'placeholder' => 'Выберите статистику'
						]
					])->label('Тип статистики') ?>
				</div>
				<div class="col-md-2">
					<?= $form->field($parametersModel, "dropNullValues")->widget(SwitchInput::class, [
						'pluginOptions' => [
							'size' => 'mini',
							'onText' => 'Да',
							'offText' => 'Нет',
							'onColor' => 'danger',
							'offColor' => 'primary'
						]
					])->label('Отбросить пустые значения') ?>
				</div>
				<div class="col-md-1">
					<?= Html::button("Показать", ['class' => 'btn btn-success pull-right', 'type' => 'submit', 'name' => 'statistics', 'value' => true]) ?>
				</div>
			</div>
		</div>
	</div>

<?php ActiveForm::end(); ?>

<?php foreach ($aggregatedAttributes as $attribute): ?>
	<?php if ([] !== $attribute->getVirtualProperties()): ?>
		<?= DynamicAttributeWidget::widget([
			'attribute' => $attribute
		]) ?>
	<?php endif; ?>
<?php endforeach; ?>