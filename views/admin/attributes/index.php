<?php
declare(strict_types = 1);

/**
 * Шаблон списка атрибутов
 * @var View $this
 * @var UsersSearch $searchModel
 * @var ActiveDataProvider $dataProvider
 */

use app\models\users\UsersSearch;
use yii\data\ActiveDataProvider;
use yii\web\View;
use kartik\grid\GridView;
use yii\bootstrap\Html;
use kartik\grid\ActionColumn;
use app\models\dynamic_attributes\DynamicAttributes;

$this->title = 'Атрибуты';
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
				],
				[
					'content' => Html::a('Поиск', 'search', ['class' => 'btn btn-info'])
				]
			],
			'export' => false,
			'resizableColumns' => true,
			'responsive' => true,
			'columns' => [
				'id',
				[
					'value' => function($model) {
						/** @var DynamicAttributes $model */
						return $model->getRelUsers()->count();
					},
					'label' => 'Пользователи'
				],
				'name',
				'categoryName',
				[
					'class' => ActionColumn::class,
					'template' => '{update} {delete}'
				]
			]

		]); ?>
	</div>
</div>