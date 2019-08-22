<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var VacancySearch $searchModel
 * @var ActiveDataProvider $dataProvider
 */

use app\modules\salary\models\references\RefGrades;
use app\modules\users\UsersModule;
use app\modules\vacancy\VacancyModule;
use pozitronik\helpers\ArrayHelper;
use app\helpers\IconsHelper;
use app\helpers\Utils;
use app\modules\references\widgets\reference_select\ReferenceSelectWidget;
use app\modules\salary\models\references\RefLocations;
use app\modules\salary\models\references\RefSalaryPremiumGroups;
use app\modules\salary\models\references\RefUserPositions;
use app\modules\users\models\references\RefUserRoles;
use app\modules\vacancy\models\references\RefVacancyRecruiters;
use app\modules\vacancy\models\references\RefVacancyStatuses;
use app\modules\vacancy\models\Vacancy;
use app\modules\vacancy\models\VacancySearch;
use app\modules\vacancy\widgets\navigation_menu\VacancyNavigationMenuWidget;
use app\widgets\badge\BadgeWidget;
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
		'class' => Formatter::class
	],
	'columns' => [
		[
			'class' => DataColumn::class,
			'filter' => false,
			'header' => IconsHelper::menu(),
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
			'attribute' => 'vacancy_id',
			'label' => 'ID вакансии'
		],
		[
			'class' => DataColumn::class,
			'attribute' => 'location',
			'value' => static function(Vacancy $vacancy) {
				return BadgeWidget::widget([
					'models' => $vacancy->getRelRefLocation()->active()->all(),
					'useBadges' => true,
					'attribute' => 'name',
					"itemsSeparator" => false,
					"optionsMap" => RefLocations::colorStyleOptions()
				]);
			},
			'format' => 'raw',
			'filter' => ArrayHelper::getValue($searchModel, 'location'),
			'filterType' => ReferenceSelectWidget::class,
			'filterInputOptions' => ['placeholder' => 'Выберите локацию'],
			'filterWidgetOptions' => [
				'referenceClass' => RefLocations::class,
				'pluginOptions' => ['allowClear' => true, 'multiple' => true]
			]
		],
		[
			'attribute' => 'ticket_id'
		],
		[
			'class' => DataColumn::class,
			'attribute' => 'recruiter',
			'value' => static function(Vacancy $vacancy) {
				return BadgeWidget::widget([
					'models' => $vacancy->getRelRefVacancyRecruiter()->active()->all(),
					'useBadges' => true,
					'attribute' => 'name',
					"itemsSeparator" => false,
					"optionsMap" => RefVacancyRecruiters::colorStyleOptions()
				]);
			},
			'format' => 'raw',
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
			'attribute' => 'employerName',
			'format' => 'raw',
			'value' => static function(Vacancy $vacancy) {
				return BadgeWidget::widget([
					'models' => $vacancy->getRelEmployer()->active()->all(),
					'useBadges' => false,
					'attribute' => 'username',
					"itemsSeparator" => false,
					'linkScheme' => [UsersModule::to('users/profile'), 'id' => 'id']
				]);
			}
		],
		[
			'class' => DataColumn::class,
			'attribute' => 'groupName',
			'format' => 'raw',
			'value' => static function(Vacancy $vacancy) {
				return BadgeWidget::widget([
					'models' => $vacancy->relGroups,
					'useBadges' => false,
					'attribute' => 'name',
					"itemsSeparator" => false,
					'linkScheme' => [VacancyModule::to('groups'), 'id' => 'id']
				]);
			}
		],
		[
			'class' => DataColumn::class,
			'attribute' => 'position',
			'value' => static function(Vacancy $vacancy) {
				return BadgeWidget::widget([
					'models' => $vacancy->getRelRefUserPosition()->active()->all(),
					'useBadges' => true,
					'attribute' => 'name',
					"itemsSeparator" => false,
					"optionsMap" => RefUserPositions::colorStyleOptions()
				]);
			},
			'format' => 'raw',
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
			'attribute' => 'premium_group',
			'value' => static function(Vacancy $vacancy) {
				return BadgeWidget::widget([
					'models' => $vacancy->getRelRefSalaryPremiumGroup()->active()->all(),
					'useBadges' => true,
					'attribute' => 'name',
					"itemsSeparator" => false,
					"optionsMap" => RefSalaryPremiumGroups::colorStyleOptions()
				]);
			},
			'format' => 'raw'
		],
		[
			'class' => DataColumn::class,
			'attribute' => 'grade',
//			'value' => 'relRefGrade.name'
			'value' => static function(Vacancy $vacancy) {
				return BadgeWidget::widget([
					'models' => $vacancy->getRelRefGrade()->active()->all(),
					'useBadges' => false,
					'attribute' => 'name',
//					"itemsSeparator" => false,
//					"optionsMap" => RefGrades::colorStyleOptions()//справочник пока не поддерживает
				]);
			},
		],
		[
			'class' => DataColumn::class,
			'attribute' => 'relRefUserRoles',
			'format' => 'raw',
			'value' => static function(Vacancy $vacancy) {
				return BadgeWidget::widget([
					'models' => $vacancy->getRelRefUserRoles()->active()->all(),//здесь нельзя использовать свойство, т.к. фреймворк не подгружает все релейшены в $_related сразу. Выяснено экспериментально, на более подробные разбирательства нет времени
					'useBadges' => true,
					'attribute' => 'name',
					"itemsSeparator" => false,
					"optionsMap" => RefUserRoles::colorStyleOptions()
				]);
			}],
		[
			'class' => DataColumn::class,
			'attribute' => 'teamleadName',
			'format' => 'raw',
			'value' => static function(Vacancy $vacancy) {
				return BadgeWidget::widget([
					'models' => $vacancy->getRelTeamlead()->active()->all(),
					'useBadges' => false,
					'attribute' => 'username',
					"itemsSeparator" => false,
					'linkScheme' => [UsersModule::to('users/profile'), 'id' => 'id']
				]);
			}
		],
		[
			'class' => DataColumn::class,
			'attribute' => 'status',
			'value' => static function(Vacancy $vacancy) {
				return BadgeWidget::widget([
					'models' => $vacancy->getRelRefVacancyStatus()->active()->all(),
					'useBadges' => true,
					'attribute' => 'name',
					"itemsSeparator" => false,
					"optionsMap" => static function() {
						return RefVacancyStatuses::colorStyleOptions();
					}
				]);
			},
			'format' => 'raw',
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
			'attribute' => 'create_date',
			'format' => 'date',
			'label' => 'Открыто',
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
			'format' => 'date',
			'label' => 'Закроется',
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