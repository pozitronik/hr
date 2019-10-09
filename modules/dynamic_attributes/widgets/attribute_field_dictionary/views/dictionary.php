<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var ArrayDataProvider $provider
 */

use kartik\grid\DataColumn;
use kartik\grid\GridView;
use yii\data\ArrayDataProvider;
use yii\i18n\Formatter;
use yii\web\View;

?>

<?= GridView::widget([
	'dataProvider' => $provider,
	'summary' => false,
	'formatter' => [
		'class' => Formatter::class,
//		'nullDisplay' => '<Не указано>'
	],
	'columns' => [
		[
			'class' => DataColumn::class,
			'attribute' => 'value',
			'label' => 'Значение'
		],
		[
			'class' => DataColumn::class,
			'attribute' => 'frequency',
			'label' => 'Частота'
		]
	]
]) ?>
