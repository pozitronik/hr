<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Groups $model
 * @var string $heading Заголовок панели (например, для отображения пути иерархии)
 */

use app\helpers\Icons;
use app\helpers\Utils;
use app\modules\groups\models\Groups;
use app\widgets\group_select\GroupSelectWidgetInterface;
use app\widgets\group_type_select\GroupTypeSelectWidget;
use app\widgets\relation_type_select\RelationTypeSelectWidget;
use yii\data\ActiveDataProvider;
use yii\web\View;
use kartik\grid\GridView;
use kartik\grid\CheckboxColumn;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\ActionColumn;

$provider = new ActiveDataProvider([
	'query' => $model->getRelChildGroups()->orderBy('name')->active()
]);

?>
<?= GridView::widget([
	'dataProvider' => $provider,
	'panel' => [
		'type' => GridView::TYPE_INFO,
		'after' => false,
		'heading' => $heading.(($provider->totalCount > 0)?" (".Utils::pluralForm($provider->totalCount, ['группа', 'группы', 'групп']).")":" (нет групп)"),
		'footer' => $provider->totalCount > $provider->pagination->pageSize?null:false,
		'before' => GroupSelectWidgetInterface::widget([
			'model' => $model,
			'attribute' => 'relChildGroups',
			'notData' => $model->isNewRecord?[]:array_merge($model->relChildGroups, [$model]),
			'multiple' => true
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
			'header' => Icons::menu(),
			'dropdown' => true,
			'dropdownButton' => [
				'label' => Icons::menu(),
				'caret' => ''
			],
			'class' => ActionColumn::class,
			'template' => '{tree}{bunch}',
			'buttons' => [
				'tree' => function($url, $model) {
					return Html::tag('li', Html::a(Icons::network().'Граф структуры', $url));
				},
				'bunch' => function($url, $model) {
					/** @var Groups $model */
					return Html::tag('li', Html::a(Icons::users_edit().'Редактирование пользователей', ['/users/bunch/index', 'group_id' => $model->id]));
				}
			]
		],
		[
			'format' => 'raw',
			'attribute' => 'name',
			'value' => function($group) {
				/** @var Groups $group */
				return Html::a($group->name, Url::to(['/groups/groups/update', 'id' => $group->id]));
			}
		],
		[
			'attribute' => 'type',
			'value' => function($group) {
				/** @var Groups $model */
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
			'value' => function($group) use ($model) {
				/** @var Groups $model */
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