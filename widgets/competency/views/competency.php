<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var array $structure
 * @var Users $user
 * @var DataProviderInterface $widgetDataProvider
 */

use app\models\users\Users;
use yii\data\DataProviderInterface;
use yii\web\View;
use kartik\grid\GridView;

?>

<?= GridView::widget([
	'dataProvider' => $widgetDataProvider,
	'panel' => false,
	'summary' => false,
	'headerRowOptions' => [
		'style' => 'display:none'
	],
	'toolbar' => false,
	'export' => false,
	'resizableColumns' => false,
	'responsive' => true,
	'options' => [
		'class' => 'competency_table'
	]
]); ?>


