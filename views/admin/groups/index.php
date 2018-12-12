<?php
declare(strict_types = 1);

/**
 * Шаблон главной страницы списка групп
 * @var View $this
 * @var GroupsSearch $searchModel
 * @var ActiveDataProvider $dataProvider
 */

use app\models\groups\GroupsSearch;
use app\models\references\refs\RefGroupTypes;
use app\models\users\Users;
use kartik\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\web\View;
use yii\bootstrap\Html;
use kartik\grid\ActionColumn;

$this->title = 'Группы';
$this->params['breadcrumbs'][] = ['label' => 'Управление', 'url' => ['/admin']];
$this->params['breadcrumbs'][] = $this->title;
//todo: фильтр/поиск по главнюку. Будет, когда определим критерий лидера (м.б. атрибут в справочнике)
?>

<div class="row">
	<div class="col-xs-12">
		<?= /** @noinspection MissedFieldInspection */
		GridView::widget([
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
					'attribute' => 'name',
					'value' => function($model) {
						/** @var GroupsSearch $model */
						return Html::a($model->name, ['update', 'id' => $model->id]);
					},
					'format' => 'raw'
				],
				[
					'attribute' => 'type',
					'value' => 'relGroupTypes.name',
					'filterType' => GridView::FILTER_SELECT2,
					'filter' => RefGroupTypes::mapData(),
					'filterInputOptions' => ['placeholder' => 'Тип'],
					'filterWidgetOptions' => ['pluginOptions' => ['allowClear' => true]]
				],
				[
					'attribute' => 'leaders',
					'value' => function($model) {
						/** @var GroupsSearch $model */
						$users = [];
						foreach ($model->leaders as $leader) {
							$users[] = Html::a($leader->username, ['admin/users/update', 'id' => $leader->id]);
						}
						return implode(", ", $users);
					},
					'format' => 'raw',
					'filterType' => GridView::FILTER_SELECT2,
					'filter' => Users::mapLeaders(),
					'filterInputOptions' => ['placeholder' => 'Руководители'],
					'filterWidgetOptions' => ['pluginOptions' => ['allowClear' => true]]
				],
				[
					'attribute' => 'usersCount',
					'label' => 'Пользователей'
				],
				[
					'attribute' => 'childGroupsCount',
					'label' => 'Подгрупп'
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