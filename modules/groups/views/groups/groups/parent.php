<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Groups $model
 * @var ActiveDataProvider $provider
 * @var string $heading Заголовок панели (например, для отображения пути иерархии)
 */

use app\helpers\IconsHelper;
use app\helpers\Utils;
use app\modules\groups\assets\GroupsAsset;
use app\modules\groups\models\Groups;
use app\modules\groups\widgets\group_select\GroupSelectWidget;
use app\modules\groups\widgets\navigation_menu\GroupNavigationMenuWidget;
use app\modules\references\widgets\group_type_select\GroupTypeSelectWidget;
use app\modules\references\widgets\relation_type_select\RelationTypeSelectWidget;
use kartik\grid\DataColumn;
use yii\data\ActiveDataProvider;
use yii\web\JsExpression;
use yii\web\View;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

GroupsAsset::register($this);

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
			'class' => DataColumn::class,
			'filter' => false,
			'header' => IconsHelper::menu(),
			'mergeHeader' => true,
			'headerOptions' => [
				'class' => 'skip-export kv-align-center kv-align-middle'
			],
			'contentOptions' => [
				'style' => 'width:50px',
				'class' => 'skip-export kv-align-center kv-align-middle'
			],
			'value' => static function(Groups $model) {
				return GroupNavigationMenuWidget::widget([
					'model' => $model,
					'mode' => GroupNavigationMenuWidget::MODE_ACTION_COLUMN_MENU
				]);
			},
			'format' => 'raw'
		],
		[
			'format' => 'raw',
			'attribute' => 'name',
			'value' => static function(Groups $group) {
				return Html::a($group->name, Url::to(['profile', 'id' => $group->id]));
			}
		],
		[
			'attribute' => 'type',
			'value' => static function(Groups $group) {
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
			'value' => static function(Groups $group) use ($model) {
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
			'header' => IconsHelper::users(),
			'footer' => Utils::pageTotal($provider, 'usersCount')
		],
		[
			'attribute' => 'childGroupsCount',
			'header' => IconsHelper::subgroups(),
			'footer' => Utils::pageTotal($provider, 'childGroupsCount')
		],
		[
			'format' => 'raw',
			'headerOptions' => [
				'class' => 'skip-export kv-align-center kv-align-middle'
			],
			'contentOptions' => [
				'style' => 'width:50px',
				'class' => 'skip-export kv-align-center kv-align-middle'
			],
			'value' => static function(Groups $group) use ($model) {
				return Html::button(IconsHelper::unlink(), ['onClick' => new JsExpression("unlink({$group->id},{$model->id}); $('tr[data-key=\"{$group->id}\"]').fadeOut()")]);
			}
		]
	]

]) ?>