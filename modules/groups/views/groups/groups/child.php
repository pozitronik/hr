<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Groups $model
 * @var ActiveDataProvider $provider
 * @var string $heading Заголовок панели (например, для отображения пути иерархии)
 */

use app\components\pozitronik\core\interfaces\widgets\SelectionWidgetInterface;
use app\components\pozitronik\navigationwidget\BaseNavigationMenuWidget;
use app\models\core\IconsHelper;
use app\modules\groups\widgets\group_type_select\GroupTypeSelectWidget;
use app\modules\groups\widgets\relation_type_select\RelationTypeSelectWidget;
use app\components\pozitronik\helpers\Utils;
use app\modules\groups\assets\GroupsAsset;
use app\modules\groups\models\Groups;
use app\modules\groups\widgets\group_select\GroupSelectWidget;
use app\modules\groups\widgets\navigation_menu\GroupNavigationMenuWidget;
use kartik\grid\DataColumn;
use yii\data\ActiveDataProvider;
use yii\web\JsExpression;
use yii\web\View;
use kartik\grid\GridView;
use yii\helpers\Html;

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
			'attribute' => 'relChildGroups',
			'exclude' => $model->isNewRecord?[]:array_merge($model->relChildGroups, [$model]),
			'multiple' => true,
			'renderingMode' => SelectionWidgetInterface::MODE_FORM
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
					'mode' => BaseNavigationMenuWidget::MODE_ACTION_COLUMN_MENU
				]);
			},
			'format' => 'raw'
		],
		[
			'format' => 'raw',
			'attribute' => 'name',
			'value' => static function(Groups $group) {
				return Html::a($group->name, ['profile', 'id' => $group->id]);
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
					'parentGroupId' => $model->id,
					'childGroupId' => $group->id,
					'showStatus' => false
				]);
			},
			'format' => 'raw'
		],
		[
			'attribute' => 'usersCount',
			'header' => IconsHelper::users(),
			'footer' => Utils::pageTotal($provider, 'usersCount'),
			'headerOptions' => ['class' => 'text-center']
		],
		[
			'attribute' => 'childGroupsCount',
			'header' => IconsHelper::subgroups(),
			'footer' => Utils::pageTotal($provider, 'childGroupsCount'),
			'headerOptions' => ['class' => 'text-center']
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
				return Html::button(IconsHelper::unlink(), ['onClick' => new JsExpression("unlink({$model->id},{$group->id}); $('tr[data-key=\"{$group->id}\"]').fadeOut()")]);
			}
		]
	]
]) ?>