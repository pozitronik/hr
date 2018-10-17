<?php
declare(strict_types = 1);

/**
 * Шаблон главной страницы списка групп
 * @var View $this
 * @var GroupsSearch $searchModel
 * @var ActiveDataProvider $dataProvider
 */

use app\models\groups\GroupsSearch;
use yii\data\ActiveDataProvider;
use yii\web\View;
use kartik\grid\GridView;
use yii\bootstrap\Html;
use kartik\grid\ActionColumn;

?>

<div class="row">
	<div class="col-xs-12">
		<?= GridView::widget([
			'dataProvider' => $dataProvider,
			'filterModel' => $searchModel,
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
				'relGroupTypes.name',
				'comment',
				[
					'class' => ActionColumn::class,
					'template' => '{tree} {update} {delete}',
					'buttons' => [
						'tree' => function($url, $model) {
							return Html::a('<span class="glyphicon glyphicon-dashboard"></span>', $url);
						}
					]

				]
			]
		]); ?>
	</div>
</div>