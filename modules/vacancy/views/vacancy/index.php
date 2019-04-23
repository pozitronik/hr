<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var VacancySearch $searchModel
 * @var ActiveDataProvider $dataProvider
 */

use app\helpers\ArrayHelper;
use app\helpers\Icons;
use app\helpers\Utils;
use app\modules\references\widgets\reference_select\ReferenceSelectWidget;
use app\modules\salary\models\references\RefLocations;
use app\modules\salary\models\references\RefUserPositions;
use app\modules\vacancy\models\references\RefVacancyRecruiters;
use app\modules\vacancy\models\references\RefVacancyStatuses;
use app\modules\vacancy\models\Vacancy;
use app\modules\vacancy\models\VacancySearch;
use app\modules\vacancy\widgets\navigation_menu\VacancyNavigationMenuWidget;
use kartik\grid\DataColumn;
use kartik\grid\GridView;
use yii\bootstrap\Html;
use yii\data\ActiveDataProvider;
use yii\i18n\Formatter;
use yii\web\View;

$this->title = 'Вакансии';
$this->params['breadcrumbs'][] = $this->title;

?>

<?= GridView::widget([
	'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
	'panel' => [
		'heading' => $this->title.(($dataProvider->totalCount > 0)?" (".Utils::pluralForm($dataProvider->totalCount, ['вакансия', 'вакансии', 'вакансий']).")":" (нет вакансий)")
	],
	'summary' => null !== $searchModel?Html::a('Создать вакансию', ['create'], ['class' => 'btn btn-success summary-content']):null,
	'showOnEmpty' => true,
	'emptyText' => Html::a('Создать вакансию', ['create'], ['class' => 'btn btn-success']),
	'toolbar' => false,
	'export' => false,
	'resizableColumns' => true,
	'responsive' => true,
	'formatter' => [
		'class' => Formatter::class,
//		'nullDisplay' => ''
	],
	'columns' => [
		[
			'class' => DataColumn::class,
			'filter' => false,
			'header' => Icons::menu(),
			'mergeHeader' => true,
			'headerOptions' => [
				'class' => 'skip-export kv-align-center kv-align-middle'
			],
			'contentOptions' => [
				'style' => 'width:50px',
				'class' => 'skip-export kv-align-center kv-align-middle'
			],
			'value' => static function(Vacancy $model) {
				return VacancyNavigationMenuWidget::widget([
					'model' => $model,
					'mode' => VacancyNavigationMenuWidget::MODE_ACTION_COLUMN_MENU
				]);
			},
			'format' => 'raw'
		],
		[
			'attribute' => 'id'
		],
		[
			'class' => DataColumn::class,
			'attribute' => 'status',
			'value' => 'relRefVacancyStatus.name',
			'filter' => ArrayHelper::getValue($searchModel, 'status'),
			'filterType' => ReferenceSelectWidget::class,
			'filterInputOptions' => ['placeholder' => 'Выберите статус'],
			'filterWidgetOptions' => [
				'referenceClass' => RefVacancyStatuses::class,
				'pluginOptions' => ['allowClear' => true, 'multiple' => true]
			]
		],
		[
			'class' => DataColumn::class,
			'attribute' => 'group',
			'value' => 'relGroup.name'

		],
		[
			'class' => DataColumn::class,
			'attribute' => 'location',
			'value' => 'relRefLocation.name',
			'filter' => ArrayHelper::getValue($searchModel, 'location'),
			'filterType' => ReferenceSelectWidget::class,
			'filterInputOptions' => ['placeholder' => 'Выберите локацию'],
			'filterWidgetOptions' => [
				'referenceClass' => RefLocations::class,
				'pluginOptions' => ['allowClear' => true, 'multiple' => true]
			]
		],
		[
			'class' => DataColumn::class,
			'attribute' => 'recruiter',
			'value' => 'relRefVacancyRecruiter.name',
			'filter' => ArrayHelper::getValue($searchModel, 'recruiter'),
			'filterType' => ReferenceSelectWidget::class,
			'filterInputOptions' => ['placeholder' => 'Выберите рекрутера'],
			'filterWidgetOptions' => [
				'referenceClass' => RefVacancyRecruiters::class,
				'pluginOptions' => ['allowClear' => true, 'multiple' => true]
			]
		],
		[
			'class' => DataColumn::class,
			'attribute' => 'position',
			'value' => 'relRefUserPosition.name',
			'filter' => ArrayHelper::getValue($searchModel, 'position'),
			'filterType' => ReferenceSelectWidget::class,
			'filterInputOptions' => ['placeholder' => 'Выберите должность'],
			'filterWidgetOptions' => [
				'referenceClass' => RefUserPositions::class,
				'pluginOptions' => ['allowClear' => true, 'multiple' => true]
			]
		],
		[
			'class' => DataColumn::class,
			'attribute' => 'role'
		],
		[
			'class' => DataColumn::class,
			'attribute' => 'teamlead'
		],
		[
			'class' => DataColumn::class,
			'attribute' => 'create_date',
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
				]
			]
		],
		[
			'class' => DataColumn::class,
			'attribute' => 'estimated_close_date',
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
				]
			]
		]
	]
]) ?>