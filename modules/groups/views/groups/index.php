<?php
declare(strict_types = 1);

/**
 * Шаблон главной страницы списка групп
 * @var View $this
 * @var GroupsSearch $searchModel
 * @var ActiveDataProvider $dataProvider
 */

use app\helpers\IconsHelper;
use app\helpers\Utils;
use app\modules\groups\GroupsModule;
use app\modules\groups\models\Groups;
use app\modules\groups\models\GroupsSearch;
use app\modules\groups\models\references\RefGroupTypes;
use app\modules\groups\widgets\navigation_menu\GroupNavigationMenuWidget;
use app\modules\references\widgets\reference_select\ReferenceSelectWidget;
use app\modules\users\models\references\RefUserRoles;
use app\modules\users\models\Users;
use app\modules\users\UsersModule;
use app\widgets\badge\BadgeWidget;
use app\modules\groups\widgets\group_card\GroupCardWidget;
use kartik\grid\DataColumn;
use kartik\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\web\View;
use yii\bootstrap\Html;

$this->title = 'Группы';
$this->params['breadcrumbs'][] = $this->title;

?>

<?= GridView::widget([
	'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
	'panel' => [
		'heading' => $this->title.(($dataProvider->totalCount > 0)?" (".Utils::pluralForm($dataProvider->totalCount, ['группа', 'группы', 'групп']).")":" (нет групп)")
	],
	'summary' => Html::a('Новая группа', GroupsModule::to(['groups/create']), ['class' => 'btn btn-success summary-content']),
	'showOnEmpty' => true,
	'emptyText' => Html::a('Новая группа', GroupsModule::to(['groups/create']), ['class' => 'btn btn-success']),
	'toolbar' => false,
	'export' => false,
	'resizableColumns' => true,
	'responsive' => true,
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
			'attribute' => 'id',
			'options' => [
				'style' => 'width:36px'

			]
		],
		[
			'attribute' => 'name',
			'value' => static function(Groups $model) {
				return BadgeWidget::widget([
					'models' => $model,
					'attribute' => 'name',
					"badgeOptions" => [
						'class' => "badge badge-info"
					],
					"optionsMap" => RefGroupTypes::colorStyleOptions(),
					"optionsMapAttribute" => 'type',
					'linkScheme' => [GroupsModule::to('groups/profile'), 'id' => $model->id]
				]);
			},
			'format' => 'raw'
		],
		[
			'class' => DataColumn::class,
			'attribute' => 'type',
			'value' => static function(Groups $group) {
				return BadgeWidget::widget([
					'models' => $group->relGroupTypes,
					'useBadges' => true,
					'attribute' => 'name',
					'unbadgedCount' => 3,
					'itemsSeparator' => false,
					"optionsMap" => RefGroupTypes::colorStyleOptions(),
					'linkScheme' => [GroupsModule::to(), 'GroupsSearch[type]' => 'id']
				]);
			},
			'format' => 'raw',
			'filterType' => ReferenceSelectWidget::class,
			'filterInputOptions' => ['placeholder' => 'Тип'],
			'filterWidgetOptions' => [
				/*В картиковском гриде захардкожено взаимодействие с собственными фильтрами, в частности использование filter. В нашем виджете обходимся так*/
				'referenceClass' => RefGroupTypes::class,
				'pluginOptions' => ['allowClear' => true]
			]
		],
		[
			'class' => DataColumn::class,
			'attribute' => 'leaders',
			'value' => static function(Groups $model) {
				return BadgeWidget::widget([
					'models' => static function() use ($model) {
						$result = [];
						foreach ($model->leaders as $leader) {
							$result[] = BadgeWidget::widget([
								'models' => RefUserRoles::getUserRolesInGroup($leader->id, $model->id),
								'attribute' => 'name',
								'useBadges' => true,
								'itemsSeparator' => false,
								"optionsMap" => static function() {
									return RefUserRoles::colorStyleOptions();
								},
								'prefix' => BadgeWidget::widget([
										'models' => $leader,
										'useBadges' => false,
										'attribute' => 'username',
										'unbadgedCount' => 3,
										'itemsSeparator' => false,
										'linkScheme' => [UsersModule::to(['users/profile']), 'id' => $leader->id]
									]).': ',
								'linkScheme' => [UsersModule::to(), 'UsersSearch[roles]' => 'id']
							]);
						}
						return $result;
					},
					'itemsSeparator' => "<span class='pull-right'>,&nbsp;</span>",
					'badgeOptions' => [
						'class' => "pull-right"
					]
				]);
			},
			'format' => 'raw',
			'filterType' => GridView::FILTER_SELECT2,
			'filter' => Users::mapLeaders(),
			'filterInputOptions' => ['placeholder' => 'Руководители'],
			'filterWidgetOptions' => ['pluginOptions' => ['allowClear' => true]]
		],
		[
			'attribute' => 'usersCount',
			'value' => static function(Groups $group) {
				return GroupCardWidget::widget(['group' => $group, 'view' => 'group_users', 'options' =>['column_view' => true]]);
			},
			'label' => 'Сотрудники',
			'headerOptions' => ['class' => 'text-center'],
			'format' => 'raw'
		],
		[
			'attribute' => 'childGroupsCount',
			'label' => 'Подгруппы',
			'value' =>  static function(Groups $group) {
				return BadgeWidget::widget([
					'models' => $group,
					'attribute' => 'childGroupsCount',
					'badgeOptions' => ['class' => 'badge pull-right'],
					'linkScheme' => [GroupsModule::to('groups/groups'), 'id' => 'id']
				]);
			},
			'format' => 'raw',
			'headerOptions' => ['class' => 'text-center']
		],
//				'comment',
	]
]) ?>