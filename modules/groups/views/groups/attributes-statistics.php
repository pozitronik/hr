<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Groups $model
 * @var DynamicAttributes[] $aggregatedAttributes
 * @var Model $parametersModel
 */

use app\modules\dynamic_attributes\models\DynamicAttributePropertyAggregation;
use app\modules\dynamic_attributes\models\DynamicAttributes;
use app\modules\dynamic_attributes\widgets\dynamic_attribute\DynamicAttributeWidget;
use app\modules\groups\GroupsModule;
use app\modules\groups\models\Groups;
use app\modules\groups\widgets\navigation_menu\GroupNavigationMenuWidget;
use kartik\form\ActiveForm;
use kartik\helpers\Html;
use kartik\select2\Select2;
use kartik\switchinput\SwitchInput;
use yii\base\Model;
use yii\web\View;

$this->title = 'Статистика по атрибутам';
$this->params['breadcrumbs'][] = GroupsModule::breadcrumbItem('Группы');
$this->params['breadcrumbs'][] = GroupsModule::breadcrumbItem($model->name, ['groups/profile', 'id' => $model->id]);
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin(); ?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="panel-control">
				<?= GroupNavigationMenuWidget::widget([
					'model' => $model
				]) ?>
			</div>
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
						<?= Html::submitButton("Показать", ['class' => 'btn btn-success pull-right']) ?>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php ActiveForm::end(); ?>

<?php if ([] === $aggregatedAttributes): ?>
	<div>Ни один атрибут сотрудников в группе не поддерживает выбранный тип статистики.</div>
<?php endif; ?>

<?php foreach ($aggregatedAttributes as $attribute): ?>
	<?php if ([] !== $attribute->getVirtualProperties()): ?>
		<?= DynamicAttributeWidget::widget([
			'attribute' => $attribute
		]) ?>
	<?php endif; ?>
<?php endforeach; ?>