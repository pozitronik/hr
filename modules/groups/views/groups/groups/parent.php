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
use app\modules\references\widgets\group_type_select\GroupTypeSelectWidget;
use app\modules\references\widgets\relation_type_select\RelationTypeSelectWidget;
use yii\data\ActiveDataProvider;
use yii\web\View;
use kartik\grid\GridView;
use kartik\grid\CheckboxColumn;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<?= GridView::widget([
	'dataProvider' => $provider,
	'panel' => [
		'type' => GridView::TYPE_INFO,
		'after' => false,
		'heading' => $heading.(($provider->totalCount > 0)?" (".Utils::pluralForm($provider->totalCount, ['группа', 'группы', 'групп']).")":" (нет групп)"),
		'footer' => $provider->totalCount > $provider->pagination->pageSize?null:false,
		'before' => GroupSelectWidget::widget([
			'model' => $model,
			'attribute' => 'relParentGroups',
			'notData' => $model->isNewRecord?[]:array_merge($model->relParentGroups, [$model]),
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
					'parentGroupId' => $group->id,
					'childGroupId' => $model->id,
					'showStatus' => false
				]);
			},
			'format' => 'raw'
		],
		[
			'attribute' => 'usersCount',
			'header' => Icons::users(),
			'footer' => Utils::pageTotal($provider, 'usersCount')
		],
		[
			'attribute' => 'childGroupsCount',
			'header' => Icons::subgroups(),
			'footer' => Utils::pageTotal($provider, 'childGroupsCount')
		],
		[
			'class' => CheckboxColumn::class,
			'headerOptions' => ['class' => 'kartik-sheet-style'],
			'header' => Icons::trash(),
			'name' => $model->formName().'[dropParentGroups]'
		]
	]

]); ?>