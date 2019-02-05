<?php
declare(strict_types = 1);

/**
 * Шаблон списка атрибутов
 * @var View $this
 * @var UsersSearch $searchModel
 * @var ActiveDataProvider $dataProvider
 */

use app\helpers\Icons;
use app\helpers\Utils;
use app\modules\dynamic_attributes\models\DynamicAttributes;
use app\models\users\UsersSearch;
use yii\bootstrap\ButtonGroup;
use yii\data\ActiveDataProvider;
use yii\web\View;
use kartik\grid\GridView;
use yii\bootstrap\Html;
use kartik\grid\ActionColumn;

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
				'heading' => $this->title.(($dataProvider->totalCount > 0)?" (".Utils::pluralForm($dataProvider->totalCount, ['атрибут', 'атрибута', 'атрибутов']).")":" (нет атрибутов)")
			],
			'summary' => ButtonGroup::widget([
				'options' => [
					'class' => 'summary-content'
				],
				'buttons' => [
					Html::a('Новый атрибут', 'create', ['class' => 'btn btn-success']),
					Html::a('Поиск', 'search', ['class' => 'btn btn-info'])
				]
			]),
			'toolbar' => false,
			'export' => false,
			'resizableColumns' => true,
			'responsive' => true,
			'columns' => [
				[
					'class' => ActionColumn::class,
					'header' => Icons::menu(),
					'dropdown' => true,
					'dropdownButton' => [
						'label' => Icons::menu(),
						'caret' => ''
					],
					'template' => '{update} {delete}',
					'buttons' => [
						'update' => function($url, $model) {
							/** @var DynamicAttributes $model */
							return Html::tag('li', Html::a(Icons::update().'Изменение', ['update', 'id' => $model->id]));
						},
						'delete' => function($url, $model) {
							/** @var DynamicAttributes $model */
							return Html::tag('li', Html::a(Icons::delete().'Удаление', ['delete', 'id' => $model->id], [
								'title' => 'Удалить запись',
								'data' => [
									'confirm' => $model->deleted?'Вы действительно хотите восстановить запись?':'Вы действительно хотите удалить запись?',
									'method' => 'post'
								]
							]));
						}
					]
				],
				[
					'attribute' => 'id',
					'options' => [
						'style' => 'width:36px'

					]
				],
				[
					'attribute' => 'name',
					'value' => function($model) {
						/** @var DynamicAttributes $model */
						return Html::a($model->name, ['update', 'id' => $model->id]);
					},
					'format' => 'raw'
				],
				'categoryName',
				[
					'attribute' => 'usersCount',
					'header' => Icons::users(),
					'headerOptions' => ['class' => 'text-center']
				]
			]

		]); ?>
	</div>
</div>