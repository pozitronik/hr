<?php
declare(strict_types = 1);

/**
 * Шаблон списка компетенций
 * @var View $this
 * @var UsersSearch $searchModel
 * @var ActiveDataProvider $dataProvider
 */

use app\models\users\UsersSearch;
use yii\data\ActiveDataProvider;
use yii\web\View;
use kartik\grid\GridView;
use yii\bootstrap\Html;
use yii\grid\ActionColumn;
use app\models\competencies\Competencies;

$this->title = 'Компетенции';
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
				[
					'value' => function($model) {
						/** @var Competencies $model */
						return $model->getRelUsers()->count();
					},
					'label' => 'Пользователи'
				],
				'name',
				[
					'attribute' => 'category',
					'value' => function($model) {
						/** @var Competencies $model */
						return Competencies::CATEGORIES[$model->category];
					}
				],
				[
					'class' => ActionColumn::class,
					'template' => '{update} {delete}'
				]
			]

		]); ?>
	</div>
</div>