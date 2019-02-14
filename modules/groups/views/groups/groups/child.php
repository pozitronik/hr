<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Groups $model
 * @var ActiveDataProvider $provider
 * @var string $heading Заголовок панели (например, для отображения пути иерархии)
 */

use app\helpers\Icons;
use app\helpers\Utils;
use app\modules\groups\models\Groups;
use app\modules\groups\widgets\group_select\GroupSelectWidget;
use app\modules\groups\widgets\navigation_menu\NavigationMenuWidget;
use app\modules\references\widgets\group_type_select\GroupTypeSelectWidget;
use app\modules\references\widgets\relation_type_select\RelationTypeSelectWidget;
use yii\data\ActiveDataProvider;
use yii\web\View;
use kartik\grid\GridView;
use kartik\grid\CheckboxColumn;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<?= /** @noinspection MissedFieldInspection */
GridView::widget([
	'dataProvider' => $provider,
	'panel' => [
		'type' => GridView::TYPE_INFO,
		'after' => false,
		'heading' => $heading.(($provider->totalCount > 0)?" (".Utils::pluralForm($provider->totalCount, ['группа', 'группы', 'групп']).")":" (нет групп)"),
		'footer' => $provider->totalCount > $provider->pagination->pageSize?null:false,
		'before' => GroupSelectWidget::widget([
			'model' => $model,
			'attribute' => 'relChildGroups',
			'notData' => $model->isNewRecord?[]:array_merge($model->relChildGroups, [$model]),
			'multiple' => true,
			'mode' => GroupSelectWidget::MODE_FORM
		])
	],
	'toolbar' => false,
	'export' => false,
	'summary' => false,
	'resizableColumns' => true,
	'responsive' => true,
	'showFooter' => true,
	'footerRowOptions' => [],
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
			'format' => 'raw',
			'attribute' => 'name',
			'value' => function(Groups $group) {
				return Html::a($group->name, Url::to(['/groups/groups/profile', 'id' => $group->id]));
			}
		],
		[
			'attribute' => 'type',
			'value' => function(Groups $group) {
				return GroupTypeSelectWidget::widget([
					'groupId' => $group->id,
					'showStatus' => false
				]);
			},
			'format' => 'raw'
		],
		[
			'attribute' => 'relGroupsGroupsChild.refGroupsRelationTypes.name',
			'label' => 'Тип связи',
			'value' => function(Groups $group) use ($model) {
				return RelationTypeSelectWidget::widget([
					'parentGroupId' => $model->id,
					'childGroupId' => $group->id,
					'showStatus' => false
				]);
			},
			'format' => 'raw'
		],
		[
			'attribute' => 'usersCount',
			'header' => Icons::users(),
			'footer' => Utils::pageTotal($provider, 'usersCount'),
			'headerOptions' => ['class' => 'text-center']
		],
		[
			'attribute' => 'childGroupsCount',
			'header' => Icons::subgroups(),
			'footer' => Utils::pageTotal($provider, 'childGroupsCount'),
			'headerOptions' => ['class' => 'text-center']
		],
		[
			'class' => CheckboxColumn::class,
			'headerOptions' => ['class' => 'kartik-sheet-style'],
			'header' => Icons::trash(),
			'name' => $model->formName().'[dropChildGroups]'
		]
	]
]); ?>