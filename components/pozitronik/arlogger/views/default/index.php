<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var ActiveRecordLoggerSearch $searchModel
 * @var ActiveDataProvider $dataProvider
 */

use app\components\pozitronik\arlogger\models\ActiveRecordLogger;
use app\components\pozitronik\arlogger\models\ActiveRecordLoggerSearch;
use yii\data\ActiveDataProvider;
use yii\grid\DataColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\i18n\Formatter;
use yii\web\View;

?>

<?= GridView::widget([
	'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
	'summary' => false,
	'showOnEmpty' => false,
	'formatter' => [
		'class' => Formatter::class,
		'nullDisplay' => ''
	],
	'columns' => [
		[
			'attribute' => 'eventType',
			'value' => static function(ActiveRecordLogger $model) {
				return $model->event->eventCaption;
			},
			'format' => 'raw'
		],
		[
			'class' => DataColumn::class,
			'attribute' => 'at',
			'value' => 'at'
		],
		[
			'class' => DataColumn::class,
			'attribute' => 'model',
			'value' => static function(ActiveRecordLogger $model) {
				return null === $model->model_key?$model->model:Html::a($model->model, ['show', 'for' => $model->model, 'id' => $model->model_key]);
			},
			'format' => 'raw',
			'filter' => $searchModel->model

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

