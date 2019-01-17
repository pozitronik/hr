<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var ActiveRecord $searchModel
 * @var ActiveDataProvider $dataProvider
 * @var $messages array
 */

use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\web\View;
use kartik\grid\GridView;

?>


<?= GridView::widget([
	'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
	'panel' => [
		'heading' => $this->title
	],
	'toolbar' => false,
	'export' => false,
	'resizableColumns' => true,
	'responsive' => true,
	'columns' => []
]); ?>
