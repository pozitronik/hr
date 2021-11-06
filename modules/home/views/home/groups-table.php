<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var GroupsSearch $searchModel
 * @var ActiveDataProvider $dataProvider
 * @var string|null $title
 * @var string|null $userLink
 */

use app\components\pozitronik\badgewidget\BadgeWidget;
use app\modules\groups\GroupsModule;
use app\modules\groups\models\Groups;
use app\modules\groups\models\GroupsSearch;
use app\modules\groups\models\references\RefGroupTypes;
use app\modules\groups\widgets\group_users\GroupUsersWidget;
use app\components\pozitronik\references\widgets\reference_select\ReferenceSelectWidget;
use app\modules\users\models\references\RefUserRoles;
use app\modules\users\models\Users;
use app\modules\users\UsersModule;
use kartik\grid\DataColumn;
use kartik\grid\GridView;
use app\components\pozitronik\helpers\ArrayHelper;
use yii\bootstrap\Html;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\View;

$this->title = $title??'Мои группы';
$this->params['breadcrumbs'][] = $this->title;
?>

<?= GridView::widget([
	'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
	'panel' => [
		'heading' => (null === $userLink?Html::encode($this->title):Html::a($this->title, $userLink)).Html::a("<div class='pull-right'>Дашборд</div>", Url::current(['t' => 0]))
	],
	'summary' => false,
	'showOnEmpty' => true,
	'toolbar' => false,
	'export' => false,
	'resizableColumns' => true,
	'responsive' => true,
	'showPageSummary' => true,
	'columns' => [
		[
			'headerOptions' => ['class' => 'text-center'],
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
					'linkScheme' => [GroupsModule::to(['groups/profile', 'id' => $model->id])]

				]);
			},
			'format' => 'raw'
		],
		[
			'headerOptions' => ['class' => 'text-center'],
			'format' => 'raw',
			'class' => DataColumn::class,
			'attribute' => 'type',
			'value' => static function(Groups $model) {
				return BadgeWidget::widget([
					'models' => $model->relGroupTypes,
					'useBadges' => true,
					'attribute' => 'name',
					'unbadgedCount' => 3,
					'itemsSeparator' => false,
					"optionsMap" => RefGroupTypes::colorStyleOptions(),
					'linkScheme' => [Url::current(['GroupsSearch[type]' => $model->type])]
				]);
			},
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
			'headerOptions' => ['class' => 'text-center'],
			'format' => 'raw',
			'filterType' => GridView::FILTER_SELECT2,
			'filter' => Users::mapLeaders(),//todo: мапить только лидеров из скоупа
			'filterInputOptions' => ['placeholder' => 'Руководители'],
			'filterWidgetOptions' => ['pluginOptions' => ['allowClear' => true]]
		],
		[
			'class' => DataColumn::class,
			'headerOptions' => ['class' => 'text-center'],
			'header' => 'Сотрудники',
			'format' => 'raw',
			'value' => static function(Groups $model) {
				return GroupUsersWidget::widget(['group' => $model, 'options' => ['column_view' => true]]);
			},
			'pageSummary' => static function($summary, $data, $widget) use ($dataProvider) {
				$groupsScope = ArrayHelper::getColumn($dataProvider->models, 'id');
				$positionTypeData = Groups::getGroupScopePositionTypeData($groupsScope);
				$usersCountStat = ArrayHelper::getValue(Groups::getGroupScopeUsersCount($groupsScope), 0);
				$items[] = BadgeWidget::widget([
					'models' => "Всего: {$usersCountStat['dcount']}/{$usersCountStat['count']}",
					"badgeOptions" => [
						'class' => "badge badge-info pull-left"
					]

				]);
				foreach ($positionTypeData as $key => $positionType) {
					$items[] = BadgeWidget::widget([
						'models' => "{$positionType->name}: $positionType->count",
						"badgeOptions" => [
							'style' => $positionType->style,
							'class' => 'badge pull-left'
						]
					]);
				}

				$vacancyCount = Groups::getGroupScopeVacancyCount($groupsScope);
				$items[] = BadgeWidget::widget([
					'models' => "Вакансии: {$vacancyCount}",
					"badgeOptions" => [
						'class' => "badge pull-right ".($vacancyCount > 0?"badge-danger":"badge-unimportant")
					]
				]);

				return implode('', $items);
			}
		]
	]
]) ?>

