<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var DynamicAttributesSearchCollection $model
 * @var ActiveDataProvider $dataProvider
 * @var array $attribute_data
 */

use app\models\references\refs\RefAttributesTypes;
use app\modules\dynamic_attributes\assets\SearchAsset;
use app\modules\dynamic_attributes\models\DynamicAttributesSearchCollection;
use yii\data\ActiveDataProvider;
use yii\web\View;
use kartik\form\ActiveForm;
use yii\helpers\Html;
use kartik\switchinput\SwitchInput;
use kartik\select2\Select2;

SearchAsset::register($this);

$this->title = 'Поиск';
$this->params['breadcrumbs'][] = ['label' => 'Управление', 'url' => ['/admin']];
$this->params['breadcrumbs'][] = ['label' => 'Атрибуты', 'url' => ['/admin/attributes']];
$this->params['breadcrumbs'][] = $this->title;

?>

<?php $form = ActiveForm::begin(); ?>
	<div class="panel panel-default">
	<div class="panel-heading">
		<div class="panel-control">
			<?= Html::button("", ['class' => 'hidden', 'type' => 'submit', 'name' => 'search', 'value' => true]); ?>
			<?= Html::button("<i class='glyphicon glyphicon-minus'></i>", ['class' => 'btn btn-danger', 'type' => 'submit', 'name' => 'remove', 'value' => count($model->searchItems) > 1, 'disabled' => (count($model->searchItems) > 1)?false:'disabled', 'title' => 'Убрать условие']); ?>
			<?= Html::button("<i class='glyphicon glyphicon-plus'></i>", ['class' => 'btn btn-success', 'type' => 'submit', 'name' => 'add', 'value' => true, 'title' => 'Добавить условие']); ?>
		</div>
		<h3 class="panel-title"><?= Html::encode($this->title); ?></h3>
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
					])->label('Искать в группах'); ?>
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
					])->label('Поиск в дочерних группах'); ?>
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
							])->label('Объединение'); ?>
						</div>
						<div class="col-md-2">
							<?= $form->field($model, "searchItems[$index][type]")->widget(Select2::class, [
								'data' => RefAttributesTypes::mapData(),
								'options' => array_merge([
									'multiple' => true,
									'placeholder' => 'Выбрать тип отношения',
									'data-tag' => "search-type",
									'data-index' => $index
								])
							])->label('Тип отношения атрибута'); ?>
						</div>
						<div class="col-md-3">
							<?= $form->field($model, "searchItems[$index][attribute]")->widget(Select2::class, [
								'data' => $attribute_data,
								'options' => [
									'multiple' => false,
									'placeholder' => 'Выбрать атрибут',
									'data-tag' => "search-attribute",
									'data-index' => $index,
									'onchange' => 'attribute_changed($(this))'
								]
							])->label('Атрибут'); ?>
						</div>

						<div class="col-md-2">
							<?= $form->field($model, "searchItems[$index][property]")->widget(Select2::class, [
								'data' => $model->attributeProperties($condition->attribute),
								'options' => [
									'multiple' => false,
									'placeholder' => 'Выбрать свойство',
									'data-tag' => "search-property",
									'data-index' => $index,
									'onchange' => 'property_changed($(this))',
									'options' => $model->propertyTypes($condition->attribute)
								]
							])->label('Свойство'); ?>
						</div>
						<div class="col-md-2">
							<?= $form->field($model, "searchItems[$index][condition]")->widget(Select2::class, [
								'data' => $model->propertiesConditions($condition->attribute, $condition->property),
								'options' => [
									'multiple' => false,
									'placeholder' => 'Выбрать условие',
									'data-tag' => "search-condition",
									'data-index' => $index
								]
							])->label('Условие'); ?>
						</div>
						<div class="col-md-2">
							<?= $form->field($model, "searchItems[$index][value]")->textInput()->label('Значение'); ?>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

		<div class="panel-footer">
			<div class="btn-group">
				<?= Html::button("Поиск", ['class' => 'btn btn-success', 'type' => 'submit', 'name' => 'search', 'value' => true]); ?>

			</div>
		</div>
	</div>
<?php ActiveForm::end(); ?>

<?php if (null !== $dataProvider): ?>
	<?= $this->render('search_result', [
		'dataProvider' => $dataProvider
	]) ?>
<?php endif; ?>