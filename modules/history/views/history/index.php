<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var ActiveRecordLoggerSearch $searchModel
 * @var ActiveDataProvider $dataProvider
 */

use pozitronik\helpers\IconsHelper;
use pozitronik\helpers\Utils;
use app\modules\history\models\ActiveRecordLogger;
use app\modules\history\models\ActiveRecordLoggerSearch;
use app\modules\history\models\references\RefModels;
use pozitronik\references\widgets\reference_select\ReferenceSelectWidget;
use app\modules\users\models\Users;
use kartik\grid\DataColumn;
use kartik\grid\GridView;
use kartik\select2\Select2;
use yii\bootstrap\Html;
use yii\data\ActiveDataProvider;
use yii\i18n\Formatter;
use yii\web\View;

$this->title = 'История';
$this->params['breadcrumbs'][] = $this->title;
?>

<?= GridView::widget([
	'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
	'panel' => [
		'heading' => $this->title.(($dataProvider->totalCount > 0)?" (".Utils::pluralForm($dataProvider->totalCount, ['запись', 'записи', 'записей']).")":" (нет записей)")
	],
	'summary' => false,
	'showOnEmpty' => false,
	'toolbar' => false,
	'export' => false,
	'resizableColumns' => true,
	'responsive' => true,
	'formatter' => [
		'class' => Formatter::class,
		'nullDisplay' => ''
	],
	'columns' => [
		[
			'attribute' => 'eventType',
			'value' => static function(ActiveRecordLogger $model) {
				return IconsHelper::event_icon($model->eventType);
			},
			'format' => 'raw'
		],
		[
			'class' => DataColumn::class,
			'filterType' => GridView::FILTER_DATE_RANGE,
			'filterWidgetOptions' => [
				'pluginOptions' => [
					'locale' => [
						'format' => 'DD.MM.YYYY',
						'separator' => ' по '
					],
					'autoclose' => true,
					'format' => 'DD.MM.YYYY',
					'separator' => ' по ',
					'alwaysShowCalendars' => true
				],
//				'presetDropdown' => true
			],
			'attribute' => 'at',
			'value' => 'at'
		],
		[
			'attribute' => 'username',
			'value' => static function(ActiveRecordLogger $model) {
				return null === $model->user?'System':Users::a($model->relUser->username, ['users/profile', 'id' => $model->user]);
			},
			'format' => 'raw'
		],
		[
			'class' => DataColumn::class,
			'attribute' => 'model',
			'value' => static function(ActiveRecordLogger $model) {
				return null === $model->model_key?$model->model:Html::a($model->model, ['show', 'for' => $model->model, 'id' => $model->model_key]);
			},
			'format' => 'raw',
			'filter' => $searchModel->model,
			'filterType' => ReferenceSelectWidget::class,
			'filterInputOptions' => ['placeholder' => 'Фильтр по источнику'],
			'filterWidgetOptions' => [
				'referenceClass' => RefModels::class,
				'size' => Select2::SMALL,
				'showEditAddon' => false,
				'pluginOptions' => [
					'allowClear' => true, 'multiple' => true
				]
			]

		],
		[
			'attribute' => 'model_key',
			'value' => static function(ActiveRecordLogger $model) {
				return null === $model->model_key?$model->model_key:Html::a($model->model_key, ['show', 'for' => $model->model, 'id' => $model->model_key]);
			},
			'format' => 'raw'

		],
		[
			'attribute' => 'actions',
			'filter' => false,
			'format' => 'raw',
			'value' => static function(ActiveRecordLogger $model) {
				return $model->event->timelineEntry->content;
			}
		]
	]
]) ?>
