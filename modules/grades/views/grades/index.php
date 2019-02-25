<?php
declare(strict_types = 1);

use kartik\grid\ActionColumn;
use kartik\grid\GridView;
use yii\data\DataProviderInterface;
use yii\helpers\Html;
use yii\web\View;

/**
 * @var View $this
 * @var DataProviderInterface $dataProvider
 */
?>

<?=
GridView::widget([
	'dataProvider' => $dataProvider,
//	'filterModel' => $searchModel,
	'panel' => [
		'heading' => $this->title
	],
	'toolbar' => [
		[
			'content' => Html::a('Новый', 'create', ['class' => 'btn btn-success'])
		]
	],
	'export' => false,
	'resizableColumns' => true,
	'responsive' => true,
	'columns' => [
	]
]); ?>

