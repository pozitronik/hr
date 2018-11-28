<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var CompetenciesSearchCollection $model
 * @var ActiveDataProvider $dataProvider
 * @var array $competency_data
 */

use app\assets\AppAsset;
use app\models\prototypes\CompetenciesSearchCollection;
use app\models\users\Users;
use app\widgets\competency\CompetencyWidget;
use app\widgets\user\UserWidget;
use kartik\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\web\View;
use kartik\form\ActiveForm;
use yii\helpers\Html;
use kartik\switchinput\SwitchInput;
use kartik\select2\Select2;
use app\models\competencies\Competencies;
use yii\helpers\Url;

$this->title = 'Поиск';
$this->params['breadcrumbs'][] = ['label' => 'Управление', 'url' => ['/admin']];
$this->params['breadcrumbs'][] = ['label' => 'Компетенции', 'url' => ['/admin/competencies']];
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsFile('js/competency_search.js', ['depends' => AppAsset::class]);//todo: после прототипирования вытащить в виджет или модуль

?>

<?php $form = ActiveForm::begin(); ?>
	<div class="panel panel-primary">
		<div class="panel-heading">
			<div class="panel-control">
				<?= Html::button("", ['class' => 'hidden', 'type' => 'submit', 'name' => 'search', 'value' => true]); ?>
				<?= Html::button("<i class='glyphicon glyphicon-minus'></i>", ['class' => 'btn btn-danger', 'type' => 'submit', 'name' => 'remove', 'value' => (count($model->searchItems) > 1), 'disabled' => (count($model->searchItems) > 1)?false:'disabled', 'title' => 'Убрать условие']); ?>
				<?= Html::button("<i class='glyphicon glyphicon-plus'></i>", ['class' => 'btn btn-success', 'type' => 'submit', 'name' => 'add', 'value' => true, 'title' => 'Добавить условие']); ?>
			</div>
			<h3 class="panel-title"><?= Html::encode($this->title); ?></h3>
		</div>

		<div class="panel-body">
			<?php foreach ($model->searchItems as $index => $condition): ?>
				<div class="row" data-index='<?= $index ?>'>
					<div class="col-xs-12">
						<div class="col-md-1">
							<?= $form->field($model, "searchItems[$index][logic]")->widget(SwitchInput::class, [
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
							<?= $form->field($model, "searchItems[$index][competency]")->widget(Select2::class, [
								'data' => $competency_data,
								'options' => [
									'multiple' => false,
									'placeholder' => 'Выбрать компетенцию',
									'data-tag' => "search-competency",
									'data-index' => $index,
									'onchange' => 'competency_changed($(this))'
								]
							])->label('Компетенция'); ?>
						</div>

						<div class="col-md-3">
							<?= $form->field($model, "searchItems[$index][field]")->widget(Select2::class, [
								'data' => $model->competencyFields($condition->competency),
								'options' => [
									'multiple' => false,
									'placeholder' => 'Выбрать поле',
									'data-tag' => "search-field",
									'data-index' => $index,
									'onchange' => 'field_changed($(this))',
									'options' => $model->fieldsTypes($condition->competency)
								]
							])->label('Поле'); ?>
						</div>
						<div class="col-md-2">
							<?= $form->field($model, "searchItems[$index][condition]")->widget(Select2::class, [
								'data' => $model->fieldsConditions($condition->competency, $condition->field),
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
//			'id',
//			[
//				'value' => function($column) {
//					/** @var Users $column */
//					return Html::img($column->avatar, ['class' => 'img-circle img-xs']);
//				},
//				'label' => 'Аватар',
//				'format' => 'raw'
//			],
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
//			'username',
//			'positionName',
			[
				'label' => 'Компетенции',
				'format' => 'raw',
				'value' => function($userModel) {
					/** @var Users $userModel */
					return GridView::widget([
						'dataProvider' => new ActiveDataProvider([
							'query' => $userModel->getRelCompetencies()->orderBy('name')
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
							'class' => 'competency_table'
						],
						'columns' => [
							[
								'attribute' => 'name',
								'value' => function($model) use ($userModel) {
									/** @var Competencies $model */
									return Html::a($model->name, Url::to(['admin/users/competencies', 'user_id' => $userModel->id, 'competency_id' => $model->id]));
								},
								'format' => 'raw'
							],
							'categoryName',
							[
								'label' => 'Данные',
								'value' => function($model) use ($userModel) {
									/** @var Competencies $model */
									return CompetencyWidget::widget([
										'user_id' => $userModel->id,
										'competency_id' => $model->id
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