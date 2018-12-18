<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var DynamicAttributesSearchCollection $model
 * @var ActiveDataProvider $dataProvider
 * @var array $attribute_data
 */

use app\assets\DynamicAttributesSearchAsset;
use app\models\dynamic_attributes\DynamicAttributesSearchCollection;
use app\models\users\Users;
use app\widgets\badge\BadgeWidget;
use app\widgets\user_attributes\UserAttributesWidget;
use app\widgets\user\UserWidget;
use kartik\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\web\View;
use kartik\form\ActiveForm;
use yii\helpers\Html;
use kartik\switchinput\SwitchInput;
use kartik\select2\Select2;
use app\models\dynamic_attributes\DynamicAttributes;
use yii\helpers\Url;
DynamicAttributesSearchAsset::register($this);

$this->title = 'Поиск';
$this->params['breadcrumbs'][] = ['label' => 'Управление', 'url' => ['/admin']];
$this->params['breadcrumbs'][] = ['label' => 'Атрибуты', 'url' => ['/admin/attributes']];
$this->params['breadcrumbs'][] = $this->title;

?>

<?php $form = ActiveForm::begin(); ?>
	<div class="panel panel-primary">
		<div class="panel-heading">
			<div class="panel-control">
				<?= Html::button("", ['class' => 'hidden', 'type' => 'submit', 'name' => 'search', 'value' => true]); ?>
				<?= Html::button("<i class='glyphicon glyphicon-minus'></i>", ['class' => 'btn btn-danger', 'type' => 'submit', 'name' => 'remove', 'value' => count($model->searchItems) > 1, 'disabled' => (count($model->searchItems) > 1)?false:'disabled', 'title' => 'Убрать условие']); ?>
				<?= Html::button("<i class='glyphicon glyphicon-plus'></i>", ['class' => 'btn btn-success', 'type' => 'submit', 'name' => 'add', 'value' => true, 'title' => 'Добавить условие']); ?>
			</div>
			<h3 class="panel-title"><?= Html::encode($this->title); ?></h3>
		</div>

		<div class="panel-body">
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

						<div class="col-md-3">
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
						<div class="col-md-3">
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
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'panel' => [
			'heading' => 'Результат'
		],
		'toolbar' => false,
		'export' => false,
		'resizableColumns' => true,
		'responsive' => true,
		'columns' => [
			[
				'attribute' => 'username',
				'value' => function($model) {
					/** @var Users $model */
					return UserWidget::widget([
						'user' => $model
					]);
				},
				'format' => 'raw',
				'label' => 'Сотрудник'
			],
			[
				'attribute' => 'groupName',
				'label' => 'Группы',
				'value' => function($model) {
					/** @var Users $model */
					return BadgeWidget::widget([
						'data' => $model->relGroups,
						'useBadges' => false,
						'attribute' => 'name',
						'linkScheme' => ['admin/groups/update', 'id' => 'id']
					]);
				},
				'format' => 'raw'
			],
//			'positionName',
			[
				'label' => 'Атрибуты',
				'format' => 'raw',
				'value' => function($userModel) {
					/** @var Users $userModel */
					return GridView::widget([
						'dataProvider' => new ActiveDataProvider([
							'query' => $userModel->getRelDynamicAttributes()->orderBy('name')->active()
						]),
						'showFooter' => false,
						'showPageSummary' => false,
						'summary' => '',
						'panel' => false,
						'toolbar' => false,
						'export' => false,
						'resizableColumns' => true,
						'responsive' => true,
						'options' => [
							'class' => 'attribute_table'
						],
						'columns' => [
							[
								'attribute' => 'name',
								'value' => function($model) use ($userModel) {
									/** @var DynamicAttributes $model */
									return Html::a($model->name, Url::to(['admin/users/attributes', 'user_id' => $userModel->id, 'attribute_id' => $model->id]));
								},
								'format' => 'raw'
							],
							'categoryName',
							[
								'label' => 'Данные',
								'value' => function($model) use ($userModel) {
									/** @var DynamicAttributes $model */
									return UserAttributesWidget::widget([
										'user_id' => $userModel->id,
										'attribute_id' => $model->id
									]);
								},
								'format' => 'raw'
							]
						]

					]);
				}
			]
		]
	]); ?>
<?php endif; ?>