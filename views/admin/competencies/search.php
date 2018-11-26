<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var CompetenciesSearchCollection $model
 * @var ActiveDataProvider $dataProvider
 */

use app\assets\AppAsset;
use app\models\prototypes\CompetenciesSearchCollection;
use app\models\users\Users;
use app\widgets\competency\CompetencyWidget;
use kartik\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\web\View;
use kartik\form\ActiveForm;
use yii\helpers\Html;
use kartik\switchinput\SwitchInput;
use kartik\select2\Select2;
use app\helpers\ArrayHelper;
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
				<?= Html::button("<i class='glyphicon glyphicon-minus'></i>", ['class' => 'btn btn-danger', 'onclick' => 'removeCondition()']); ?>
				<?= Html::button("<i class='glyphicon glyphicon-plus'></i>", ['class' => 'btn btn-success', 'type' => 'submit', 'name' => 'add', 'value' => true]); ?>
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
								'data' => ArrayHelper::cmap(Competencies::find()->active()->all(), 'id', ['name', 'categoryName'], ' => '),//todo: группировка по категориям
								'options' => [
									'multiple' => false,
									'placeholder' => 'Выбрать компетенцию',
									'data-competency' => $index,
									'onchange' => 'competency_changed($(this))'
								]
							])->label('Компетенция'); ?>
						</div>

						<div class="col-md-3">
							<?= $form->field($model, "searchItems[$index][field]")->widget(Select2::class, [
								'data' => $model->competencyFields($model->searchItems[$index]->competency),
								'options' => [
									'multiple' => false,
									'placeholder' => 'Выбрать поле',
									'data-field' => $index,
									'onchange' => 'field_changed($(this))'
								]
							])->label('Поле'); ?>
						</div>
						<div class="col-md-2">
							<?= $form->field($model, "searchItems[$index][condition]")->widget(Select2::class, [
								'data' => $model->fieldsConditions($model->searchItems[$index]->competency, $model->searchItems[$index]->field),
								'options' => [
									'multiple' => false,
									'placeholder' => 'Выбрать условие',
									'data-condition' => $index
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
				<?= Html::submitButton('Поиск', ['class' => 'btn btn-primary']); ?>
			</div>
			<div class="btn-group pull-right">
				<?= Html::button('Сохранить поиск', ['class' => 'btn btn-info']); ?>
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
			'id',
			[
				'value' => function($column) {
					/** @var Users $column */
					return Html::img($column->avatar, ['class' => 'img-circle img-xs']);
				},
				'label' => 'Аватар',
				'format' => 'raw'
			],
			'username',
			'positionName',
			[
				'label' => 'Компетенции',
				'format' => 'raw',
				'value' => function($user) {
					return GridView::widget([
						'dataProvider' => new ActiveDataProvider([
							'query' => $user->getRelCompetencies()->orderBy('name')
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
								'value' => function($model) use ($user) {
									/** @var Competencies $model */
									return Html::a($model->name, Url::to(['admin/users/competencies', 'user_id' => $user->id, 'competency_id' => $model->id]));
								},
								'format' => 'raw'
							],
							'categoryName',
							[
								'label' => 'Данные',
								'value' => function($model) use ($user) {
									/** @var Competencies $model */
									return CompetencyWidget::widget([
										'user_id' => $user->id,
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