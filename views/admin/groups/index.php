<?php
declare(strict_types = 1);

/**
 * Шаблон главной страницы списка групп
 * @var View $this
 * @var GroupsSearch $searchModel
 * @var ActiveDataProvider $dataProvider
 */

use app\helpers\Icons;
use app\helpers\Utils;
use app\models\groups\Groups;
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
?>

<div class="row">
	<div class="col-xs-12">
		<?= /** @noinspection MissedFieldInspection */
		GridView::widget([
			'dataProvider' => $dataProvider,
			'filterModel' => $searchModel,
			'panel' => [
				'heading' => $this->title.(($dataProvider->totalCount > 0)?" (".Utils::pluralForm($dataProvider->totalCount, ['группа', 'группы', 'групп']).")":" (нет групп)")
			],
			'summary' => Html::a('Новая группа', 'create', ['class' => 'btn btn-success', 'style' => 'margin: 10px 10px auto auto']),
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
					'template' => '{tree} {update} {delete} ',
					'buttons' => [
						'tree' => function($url, $model) {
							return Html::tag('li', Html::a(Icons::network().' Граф структуры', $url));
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
						/** @var Groups $model */
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
					'header' => Icons::users(),
				],
				[
					'attribute' => 'childGroupsCount',
					'header' => Icons::subgroups(),
				],
//				'comment',
			]
		]); ?>
	</div>
</div>