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

use app\helpers\Icons;
use app\modules\groups\models\Groups;
use app\modules\references\widgets\roles_select\RolesSelectWidget;
use app\modules\vacancy\models\Vacancy;
use app\modules\vacancy\widgets\navigation_menu\VacancyNavigationMenuWidget;
use kartik\grid\DataColumn;
use kartik\grid\GridView;
use yii\bootstrap\Html;
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
		'before' => Html::a('Создать вакансию', ['create', 'group' => $model->id], ['class' => 'btn btn-success summary-content'])
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
			'class' => DataColumn::class,
			'attribute' => 'create_date',
			'format' => 'date',
			'label' => 'Открыто',
		],
		[
			'class' => DataColumn::class,
			'attribute' => 'position',
			'value' => 'relRefUserPosition.name'
		],
		[
			'class' => DataColumn::class,
			'attribute' => 'premium_group',
			'value' => 'relRefSalaryPremiumGroup.name'
		],
		[
			'class' => DataColumn::class,
			'attribute' => 'grade',
			'value' => 'relRefGrade.name'
		],
		[
			'class' => DataColumn::class,
			'attribute' => 'location',
			'value' => 'relRefLocation.name'
		],
		[
			'class' => DataColumn::class,
			'attribute' => 'role'//todo: уточнить, что есть роль в данном кейсе
		],
		[
			'class' => DataColumn::class,
			'attribute' => 'estimated_close_date',
			'label' => 'Закроется',
			'format' => 'date',
		]
	]
]) ?>
