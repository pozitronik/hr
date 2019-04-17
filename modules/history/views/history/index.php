<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var ActiveRecordLoggerSearch $searchModel
 * @var ActiveDataProvider $dataProvider
 */

use app\helpers\Utils;
use app\modules\history\models\ActiveRecordLogger;
use app\modules\history\models\ActiveRecordLoggerSearch;
use kartik\grid\DataColumn;
use kartik\grid\GridView;
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
			'value' => 'at',
		],
		[
			'attribute' => 'username',
			'value' => static function(ActiveRecordLogger $model) {
				return null === $model->user?'System':Html::a($model->userModel->username, ['/users/users/profile', 'id' => $model->user]);
			},
			'format' => 'raw'
		],
		[
			'attribute' => 'model'
		],
		[
			'attribute' => 'model_key'
		],
		[
			'attribute' => 'actions',
			'format' => 'raw',
			'value' => static function(ActiveRecordLogger $model) {
				return $model->event->timelineEntry->content;
			}
		]
	]
]) ?>
