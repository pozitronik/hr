<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var DynamicAttributesSearchCollection $model
 * @var ActiveDataProvider $dataProvider
 * @var array $attribute_data
 */

use app\models\core\core_module\CoreModule;
use app\modules\dynamic_attributes\models\references\RefAttributesTypes;
use app\modules\dynamic_attributes\models\DynamicAttributesSearchCollection;
use app\modules\references\widgets\reference_select\ReferenceSelectWidget;
use kartik\depdrop\DepDrop;
use yii\data\ActiveDataProvider;
use yii\web\View;
use kartik\form\ActiveForm;
use yii\helpers\Html;
use kartik\switchinput\SwitchInput;
use kartik\select2\Select2;
use yii\helpers\Url;

$this->title = 'Поиск';
$this->params['breadcrumbs'][] = CoreModule::breadcrumbItem('Атрибуты');
$this->params['breadcrumbs'][] = $this->title;

?>

<?php $form = ActiveForm::begin(); ?>
	<div class="panel panel-default">
	<div class="panel-heading">
		<div class="panel-control">
			<?= Html::button("", ['class' => 'hidden', 'type' => 'submit', 'name' => 'search', 'value' => true]) ?>
			<?= Html::button("<i class='glyphicon glyphicon-minus'></i>", ['class' => 'btn btn-danger', 'type' => 'submit', 'name' => 'remove', 'value' => count($model->searchItems) > 1, 'disabled' => (count($model->searchItems) > 1)?false:'disabled', 'title' => 'Убрать условие']) ?>
			<?= Html::button("<i class='glyphicon glyphicon-plus'></i>", ['class' => 'btn btn-success', 'type' => 'submit', 'name' => 'add', 'value' => true, 'title' => 'Добавить условие']) ?>
		</div>
		<h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
	</div>

	<div class="panel-body">
		<div class="row">
			<div class="col-xs-12">
				<div class="col-md-10">
					<?= $form->field($model, "searchScope")->widget(Select2::class, [
						'data' => $model::searchGroups(),
						'value' => $model->searchScope,
						'options' => [
							'multiple' => true,
							'placeholder' => 'Все группы'
						]
					])->label('Искать в группах') ?>
				</div>
				<div class="col-md-2">
					<?= $form->field($model, "searchTree")->widget(SwitchInput::class, [
						'pluginOptions' => [
							'size' => 'mini',
							'onText' => 'ДА',
							'offText' => 'НЕТ',
							'onColor' => 'primary',
							'offColor' => 'default'
						]
					])->label('Поиск в дочерних группах') ?>
				</div>
			</div>

			<?php foreach ($model->searchItems as $index => $condition): ?>
				<div class="row" data-index='<?= $index ?>'>
					<div class="col-xs-12">
						<div class="col-md-1">
							<?= $form->field($model, "searchItems[$index][union]")->widget(SwitchInput::class, [
								'pluginOptions' => [
									'size' => 'mini',
									'onText' => 'И',
									'offText' => 'ИЛИ',
									'onColor' => 'primary',
									'offColor' => 'primary'
								]
							])->label('Объединение') ?>
						</div>
						<div class="col-md-2">
							<?= $form->field($model, "searchItems[$index][type]")->widget(ReferenceSelectWidget::class, [
								'referenceClass' => RefAttributesTypes::class,
								'options' => array_merge([
									'multiple' => true,
									'placeholder' => 'Выбрать тип отношения'
								])
							])->label('Тип отношения атрибута') ?>
						</div>
						<div class="col-md-3">
							<?= $form->field($model, "searchItems[$index][attribute]")->widget(Select2::class, [
								'data' => $attribute_data,
								'options' => [
									'multiple' => false,
									'placeholder' => 'Выбрать атрибут'
								]
							])->label('Атрибут') ?>
						</div>


						<div class="col-md-2">
							<?= $form->field($model, "searchItems[$index][property]")->widget(DepDrop::class, [
								'data' => $model->attributeProperties($condition->attribute),
								'type' => DepDrop::TYPE_SELECT2,
								'pluginOptions' => [
									'depends' => ["dynamicattributessearchcollection-searchitems-$index-attribute"],
									'url' => Url::to(['/attributes/ajax/attribute-get-properties']),
									'loadingText' => 'Загружаю свойства'
								],
								'options' => [
									'multiple' => false,
									'placeholder' => 'Выбрать атрибут',
									'options' => $model->propertyTypes($condition->attribute)
								]
							])->label('Свойство') ?>
						</div>

						<div class="col-md-2">
							<?= $form->field($model, "searchItems[$index][condition]")->widget(DepDrop::class, [
								'data' => $model->propertiesConditions($condition->attribute, $condition->property),
								'type' => DepDrop::TYPE_SELECT2,
								'pluginOptions' => [
									'params' => ["dynamicattributessearchcollection-searchitems-$index-attribute"],
									'depends' => ["dynamicattributessearchcollection-searchitems-$index-property"],
									'url' => Url::to(['/attributes/ajax/attribute-get-property-condition']),
									'loadingText' => 'Загружаю условия'
								],
								'options' => [
									'multiple' => false,
									'placeholder' => 'Выбрать условие'
								]
							])->label('Условие') ?>
						</div>
						<div class="col-md-2">
							<?= $form->field($model, "searchItems[$index][value]")->textInput()->label('Значение') ?>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

		<div class="panel-footer">
			<div class="btn-group">
				<?= Html::button("Поиск", ['class' => 'btn btn-success', 'type' => 'submit', 'name' => 'search', 'value' => true]) ?>

			</div>
		</div>
	</div>
<?php ActiveForm::end(); ?>

<?php if (null !== $dataProvider): ?>
	<?= $this->render('search_result', [
		'dataProvider' => $dataProvider
	]) ?>
<?php endif; ?>