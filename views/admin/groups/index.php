<?php
declare(strict_types = 1);

/**
 * Шаблон главной страницы списка групп
 * @var View $this
 * @var GroupsSearch $searchModel
 * @var ActiveDataProvider $dataProvider
 */

use app\helpers\ArrayHelper;
use app\models\groups\GroupsSearch;
use yii\data\ActiveDataProvider;
use yii\web\View;
use kartik\grid\GridView;
use yii\bootstrap\Html;
use kartik\grid\ActionColumn;

$this->title = 'Команды';
$this->params['breadcrumbs'][] = ['label' => 'Управление', 'url' => ['/admin']];
$this->params['breadcrumbs'][] = $this->title;
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
				[
					'attribute' => 'relGroupTypes.name',
					'label' => 'Тип'
				],
				[
					'attribute' => 'leaders',
					'value' => function($model) {
						/** @var GroupsSearch $model */
						return implode(", ", ArrayHelper::getColumn($model->leaders, 'username'));
					}
				],
				'comment',
				[
					'class' => ActionColumn::class,
					'template' => '{tree} {update} {delete}',
					'buttons' => [
						'tree' => function($url, $model) {
							return Html::a('Граф', $url, ['class' => 'btn btn-xs btn-info']);
						}
					]

				]
			]
		]); ?>
	</div>
</div>