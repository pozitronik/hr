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
use app\modules\groups\models\Groups;
use app\modules\groups\models\GroupsSearch;
use app\modules\references\models\refs\RefGroupTypes;
use app\modules\groups\widgets\navigation_menu\NavigationMenuWidget;
use app\modules\users\models\Users;
use kartik\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\web\View;
use yii\bootstrap\Html;

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
			'summary' => Html::a('Новая группа', 'create', ['class' => 'btn btn-success summary-content']),//todo: heading not shown for empty selections
			'toolbar' => false,
			'export' => false,
			'resizableColumns' => true,
			'responsive' => true,
			'columns' => [
				[
					'filter' => false,
					'header' => Icons::menu(),
					'mergeHeader' => true,
					'headerOptions' => [
						'class' => 'skip-export kv-align-center kv-align-middle'
					],
					'contentOptions' => [
						'style' => 'width:50px',
						'class' => 'skip-export kv-align-center kv-align-middle'
					],
					'value' => function(Groups $model) {
						return NavigationMenuWidget::widget([
							'model' => $model,
							'mode' => NavigationMenuWidget::MODE_ACTION_COLUMN_MENU
						]);
					},
					'format' => 'raw'
				],
				[
					'attribute' => 'id',
					'options' => [
						'style' => 'width:36px'

					]
				],
				[
					'attribute' => 'name',
					'value' => function(Groups $model) {
						return Html::a($model->name, ['profile', 'id' => $model->id]);
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
					'value' => function(Groups $model) {
						$users = [];
						foreach ($model->leaders as $leader) {
							$users[] = Html::a($leader->username, ['/users/users/profile', 'id' => $leader->id]);
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
					'headerOptions' => ['class' => 'text-center']
				],
				[
					'attribute' => 'childGroupsCount',
					'header' => Icons::subgroups(),
					'headerOptions' => ['class' => 'text-center']
				],
//				'comment',
			]
		]); ?>
	</div>
</div>