<?php
declare(strict_types = 1);

use app\components\pozitronik\navigationwidget\BaseNavigationMenuWidget;
use app\models\core\IconsHelper;
use app\modules\targets\models\references\RefTargetsTypes;
use app\modules\targets\models\Targets;
use app\modules\targets\models\TargetsSearch;
use app\modules\targets\TargetsModule;
use app\modules\targets\widgets\navigation_menu\TargetNavigationMenuWidget;
use app\modules\users\models\Users;
use app\components\pozitronik\badgewidget\BadgeWidget;
use kartik\grid\DataColumn;
use kartik\grid\GridView;
use yii\bootstrap\Html;
use yii\data\ActiveDataProvider;
use yii\i18n\Formatter;
use yii\web\View;

/**
 * @var View $this
 * @var ActiveDataProvider $dataProvider
 * @var TargetsSearch $searchModel
 * @var Users $user
 */

$this->title = "Цели пользователя {$user->username}";
$this->params['breadcrumbs'][] = TargetsModule::breadcrumbItem('Целеполагание');
$this->params['breadcrumbs'][] = $this->title;
?>


<?= GridView::widget([
	'filterModel' => $searchModel,
	'dataProvider' => $dataProvider,
	'panel' => [
		'heading' => $this->title,
		'before' => false,
	],
	'summary' => Html::a('Все цели', TargetsModule::to(['targets/user', 'id' => $user->id]), ['class' => 'btn btn-success summary-content']),
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
					'mode' => BaseNavigationMenuWidget::MODE_ACTION_COLUMN_MENU
				]);
			},
			'format' => 'raw'
		],
		[
			'class' => DataColumn::class,
			'attribute' => 'name',
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
			'label' => 'Годовая',
			'headerOptions' => [
				'class' => 'kv-align-center kv-align-middle'
			],
			'contentOptions' => [
				'class' => 'kv-align-center kv-align-middle'
			],
			'value' => function(Targets $model) {
				if (!$model->relTargetsPeriods->is_year) return null;
				return $this->render('common/mirror-badge', [
					'model' => $model,
					'spanned' => false
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
			'value' => function(Targets $model) {
				if (!$model->relTargetsPeriods->q1) return null;
				return $this->render('common/mirror-badge', [
					'model' => $model,
					'spanned' => false
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
			'value' => function(Targets $model) {
				if (!$model->relTargetsPeriods->q2) return null;
				return $this->render('common/mirror-badge', [
					'model' => $model,
					'spanned' => false
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
			'value' => function(Targets $model) {
				if (!$model->relTargetsPeriods->q3) return null;
				return $this->render('common/mirror-badge', [
					'model' => $model,
					'spanned' => false
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
			'value' => function(Targets $model) {
				if (!$model->relTargetsPeriods->q4) return null;
				return $this->render('common/mirror-badge', [
					'model' => $model,
					'spanned' => false
				]);
			},
			'format' => 'raw'
		],
	],

	'rowOptions' => static function($record) {
		$class = '';
		if ($record['deleted']) {
			$class .= 'danger ';
		}
		return ['class' => $class];
	}
]) ?>
