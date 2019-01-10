<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var ActiveRecord $searchModel
 * @var ActiveDataProvider $dataProvider
 */

use app\models\user_rights\Privileges;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\ActiveRecord;
use yii\web\View;
use kartik\grid\GridView;
use yii\bootstrap\Html;
use kartik\grid\ActionColumn;

$this->title = 'Привилегии';
$this->params['breadcrumbs'][] = ['label' => 'Управление', 'url' => ['/admin']];
$this->params['breadcrumbs'][] = $this->title;
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
				'name',
				[
					'attribute' => 'userRights',
					'value' => function($model) {
						/** @var Privileges $model */
						return GridView::widget([
							'dataProvider' => new ArrayDataProvider([
								'allModels' => $model->userRights
							]),
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
								'class' => 'grid_view_cell'
							],
							'columns' => [
								[
									'attribute' => 'name',
									'options' => [
										'style' => 'width:20%'
									],
								],
								'description'
							]
						]);
					},
					'format' => 'raw'
				],
				[
					'class' => ActionColumn::class,
					'template' => '{update} {delete}'
				]
			]
		]); ?>
	</div>
</div>