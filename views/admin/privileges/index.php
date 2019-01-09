<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var ActiveRecord $searchModel
 * @var ActiveDataProvider $dataProvider
 */

use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\web\View;
use kartik\grid\GridView;
use yii\bootstrap\Html;
use kartik\grid\ActionColumn;

?>

<div class="row">
	<div class="col-xs-12">
		<?= GridView::widget([
			'dataProvider' => $dataProvider,
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
				'id',
				'name',
				[
					'class' => ActionColumn::class,
					'template' => '{update} {delete}'
				]
			]
		]); ?>
	</div>
</div>