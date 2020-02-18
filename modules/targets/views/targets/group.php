<?php
declare(strict_types = 1);

use app\models\core\IconsHelper;
use app\modules\dynamic_attributes\widgets\navigation_menu\AttributeNavigationMenuWidget;
use app\modules\groups\models\Groups;
use app\modules\targets\models\references\RefTargetsTypes;
use app\modules\targets\models\Targets;
use app\modules\targets\models\TargetsPeriods;
use app\modules\targets\models\TargetsSearch;
use app\modules\targets\TargetsModule;
use app\modules\targets\widgets\navigation_menu\TargetNavigationMenuWidget;
use pozitronik\widgets\BadgeWidget;
use kartik\grid\DataColumn;
use kartik\grid\GridView;
use pozitronik\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use yii\i18n\Formatter;
use yii\web\View;

/**
 * @var View $this
 * @var ActiveDataProvider $dataProvider
 * @var TargetsSearch $searchModel
 * @var Groups $group
 * @var bool $onlyMirrored -- показывать данные назначений только для зеркальных целей
 */

$this->title = "Цели группы {$group->name}";
$this->params['breadcrumbs'][] = TargetsModule::breadcrumbItem('Целеполагание');
$this->params['breadcrumbs'][] = $this->title;

$groupTargetsId = ArrayHelper::getColumn(Targets::FindGroupTargetsScope($group)->all(), 'id');
?>


<?= GridView::widget([
	'filterModel' => $searchModel,
	'dataProvider' => $dataProvider,
	'panel' => [
		'heading' => $this->title,
		'before' => false,
	],
	'summary' => false,
	'showOnEmpty' => true,
	'emptyText' => 'Нет целей',
	'toolbar' => false,
	'export' => false,
	'resizableColumns' => true,
	'responsive' => true,
	'formatter' => [
		'class' => Formatter::class,
		'nullDisplay' => '',
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
			'value' => static function(Targets $model) {
				return TargetNavigationMenuWidget::widget([
					'model' => $model,
					'mode' => AttributeNavigationMenuWidget::MODE_ACTION_COLUMN_MENU
				]);
			},
			'format' => 'raw'
		],
		[
			'class' => DataColumn::class,
			'attribute' => 'name',
			'label' => 'Веха',
			'headerOptions' => [
				'class' => 'kv-align-center kv-align-middle'
			],
			'contentOptions' => [
				'class' => 'kv-align-center kv-align-middle'
			],
			'value' => static function(Targets $model) {
				return BadgeWidget::widget([
					'models' => $model,
					'attribute' => 'name',
					"badgeOptions" => [
						'class' => "badge badge-target"
					],
					'itemsSeparator' => false,
					"optionsMap" => RefTargetsTypes::colorStyleOptions(),
					"optionsMapAttribute" => 'type',
					'linkScheme' => [TargetsModule::to('targets/update'), 'id' => 'id']
				]);
			},
			'format' => 'raw',
			'group' => true
		],
		[
			'class' => DataColumn::class,
			'attribute' => 'is_year',
			'label' => 'Годовая цель',
			'headerOptions' => [
				'class' => 'kv-align-center kv-align-middle'
			],
			'contentOptions' => [
				'class' => 'kv-align-center kv-align-middle'
			],
			'value' => function(Targets $model) use ($groupTargetsId, $onlyMirrored) {
				return $this->render('common/target-badge', [
					'models' => $model->getQuarterTargets(TargetsPeriods::PERIOD_YEAR)->andFilterWhere(['sys_targets.id' => $groupTargetsId])->all(),
					'onlyMirrored' => $onlyMirrored
				]);
			},
			'format' => 'raw'
		],
		[
			'class' => DataColumn::class,
			'attribute' => 'q1',
			'headerOptions' => [
				'class' => 'kv-align-center kv-align-middle'
			],
			'contentOptions' => [
				'class' => 'kv-align-center kv-align-middle'
			],
			'value' => function(Targets $model) use ($groupTargetsId, $onlyMirrored) {
				return $this->render('common/target-badge', [
					'models' => $model->getQuarterTargets(1)->andFilterWhere(['sys_targets.id' => $groupTargetsId])->all(),
					'onlyMirrored' => $onlyMirrored
				]);
			},
			'format' => 'raw'
		],
		[
			'class' => DataColumn::class,
			'attribute' => 'q2',
			'headerOptions' => [
				'class' => 'kv-align-center kv-align-middle'
			],
			'contentOptions' => [
				'class' => 'kv-align-center kv-align-middle'
			],
			'value' => function(Targets $model) use ($groupTargetsId, $onlyMirrored) {
				return $this->render('common/target-badge', [
					'models' => $model->getQuarterTargets(2)->andFilterWhere(['sys_targets.id' => $groupTargetsId])->all(),
					'onlyMirrored' => $onlyMirrored
				]);
			},
			'format' => 'raw'
		],
		[
			'class' => DataColumn::class,
			'attribute' => 'q3',
			'headerOptions' => [
				'class' => 'kv-align-center kv-align-middle'
			],
			'contentOptions' => [
				'class' => 'kv-align-center kv-align-middle'
			],
			'value' => function(Targets $model) use ($groupTargetsId, $onlyMirrored) {
				return $this->render('common/target-badge', [
					'models' => $model->getQuarterTargets(3)->andFilterWhere(['sys_targets.id' => $groupTargetsId])->all(),
					'onlyMirrored' => $onlyMirrored
				]);
			},
			'format' => 'raw'
		],
		[
			'class' => DataColumn::class,
			'attribute' => 'q4',
			'headerOptions' => [
				'class' => 'kv-align-center kv-align-middle'
			],
			'contentOptions' => [
				'class' => 'kv-align-center kv-align-middle'
			],
			'value' => function(Targets $model) use ($groupTargetsId, $onlyMirrored) {
				return $this->render('common/target-badge', [
					'models' => $model->getQuarterTargets(4)->andFilterWhere(['sys_targets.id' => $groupTargetsId])->all(),
					'onlyMirrored' => $onlyMirrored
				]);
			},
			'format' => 'raw'
		],
		[
			'class' => DataColumn::class,
			'attribute' => 'not_set',
			'label' => 'Не задано',
			'visible' => false,//для быстрого включения по необходимости
			'headerOptions' => [
				'class' => 'kv-align-center kv-align-middle'
			],
			'contentOptions' => [
				'class' => 'kv-align-center kv-align-middle'
			],
			'value' => function(Targets $model) use ($groupTargetsId, $onlyMirrored) {
				return $this->render('common/target-badge', [
					'models' => $model->getQuarterTargets()->andFilterWhere(['sys_targets.id' => $groupTargetsId])->all(),
					'onlyMirrored' => $onlyMirrored
				]);
			},
			'format' => 'raw'
		]
	],

	'rowOptions' => static function($record) {
		$class = '';
		if ($record['deleted']) {
			$class .= 'danger ';
		}
		return ['class' => $class];
	}
]) ?>
