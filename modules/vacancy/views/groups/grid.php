<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Groups $model
 * @var ActiveDataProvider $provider
 * @var bool $showRolesSelector Отображать колонку выбиралки роли для вакансии (отключаем в некоторых случаях для ускорения)
 * @var bool $showDropColumn Отображать колонку удаления вакансии
 *
 * @var bool|string $heading
 */

use pozitronik\helpers\IconsHelper;
use app\modules\groups\models\Groups;
use app\modules\salary\models\references\RefLocations;
use app\modules\salary\models\references\RefSalaryPremiumGroups;
use app\modules\salary\models\references\RefUserPositions;
use app\modules\users\models\references\RefUserRoles;
use app\modules\vacancy\models\Vacancy;
use app\modules\vacancy\widgets\navigation_menu\VacancyNavigationMenuWidget;
use pozitronik\widgets\BadgeWidget;
use kartik\grid\DataColumn;
use kartik\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\i18n\Formatter;
use yii\web\View;

?>

<?= GridView::widget([
	'dataProvider' => $provider,
	'panel' => [
		'after' => false,
		'heading' => $heading,
		'footer' => $provider->totalCount > $provider->pagination->pageSize?null:false,
		'before' => false
	],
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
			'class' => DataColumn::class,
			'attribute' => 'create_date',
			'format' => 'date',
			'label' => 'Открыто'
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
			'format' => 'raw'
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
			'value' => static function(Vacancy $vacancy) {
				return BadgeWidget::widget([
					'models' => $vacancy->getRelRefGrade()->active()->all(),
					'useBadges' => true,
					'attribute' => 'name',
					"itemsSeparator" => false,
//					"optionsMap" => RefGrades::colorStyleOptions()//not customizable
				]);
			}
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
			}
		],
		[
			'class' => DataColumn::class,
			'attribute' => 'relVacancyGroupRoles',
			'format' => 'raw',
			'value' => static function(Vacancy $vacancy) {
				return BadgeWidget::widget([
					'models' => $vacancy->getRelRefUserRoles()->all(),//здесь нельзя использовать свойство, т.к. фреймворк не подгружает все релейшены в $_related сразу. Выяснено экспериментально, на более подробные разбирательства нет времени
					'useBadges' => true,
					'attribute' => 'name',
					'unbadgedCount' => 6,
					"itemsSeparator" => false,
					"optionsMap" => RefUserRoles::colorStyleOptions()
				]);
			}
		],
		[
			'class' => DataColumn::class,
			'attribute' => 'estimated_close_date',
			'label' => 'Закроется',
			'format' => 'date'
		]
	]
]) ?>
