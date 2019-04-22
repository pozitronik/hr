<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var VacancySearch $searchModel
 * @var ActiveDataProvider $dataProvider
 */

use app\helpers\Icons;
use app\helpers\Utils;
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
		'nullDisplay' => ''
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
			'attribute' => 'vacancy_id'
		],
		[
			'attribute' => 'ticket_id'
		],
		[
			'attribute' => 'status',
			'value' => 'relRefVacancyStatus.name'
		],
		[
			'attribute' => 'group',
			'value' => 'relGroup.name'
		],
		[
			'attribute' => 'location',
			'value' => 'relRefLocation.name'
		],
		[
			'attribute' => 'recruiter',
			'value' => 'relRefVacancyRecruiter.name'
		],
		[
			'attribute' => 'position',
			'value' => 'relRefUserPosition.name'
		],
		[
			'attribute' => 'role'
		],
		[
			'attribute' => 'teamlead'
		],
		[
			'attribute' => 'create_date'
		],
		[
			'attribute' => 'close_date'
		],
		[
			'attribute' => 'estimated_close_date'
		]
	]
]) ?>