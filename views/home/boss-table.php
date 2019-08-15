<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var GroupsSearch $searchModel
 * @var ActiveDataProvider $dataProvider
 */

use app\modules\groups\GroupsModule;
use app\modules\groups\models\Groups;
use app\modules\groups\models\GroupsSearch;
use app\modules\groups\models\references\RefGroupTypes;
use app\modules\references\ReferencesModule;
use app\modules\references\widgets\reference_select\ReferenceSelectWidget;
use app\modules\salary\models\references\RefUserPositionTypes;
use app\modules\users\models\references\RefUserRoles;
use app\modules\users\models\Users;
use app\modules\users\UsersModule;
use app\modules\vacancy\VacancyModule;
use app\widgets\badge\BadgeWidget;
use kartik\grid\DataColumn;
use kartik\grid\GridView;
use pozitronik\helpers\ArrayHelper;
use yii\bootstrap\Html;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\View;

$this->title = 'Мои группы';
$this->params['breadcrumbs'][] = $this->title;
?>

<?= GridView::widget([
	'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
	'panel' => [
		'heading' => $this->title.Html::a("<div class='pull-right'>Дашборд</div>", Url::current(['t' => 0]))
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
					'useBadges' => false,
					'attribute' => 'name',
					'linkScheme' => [GroupsModule::to(['groups/groups']), 'id' => $model->id]
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
					"optionsMap" => static function() {
						return RefGroupTypes::colorStyleOptions();
					},
					'linkScheme' => [ReferencesModule::to(['references/update']), 'id' => 'id', 'class' => 'RefGroupTypes']
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
				$items = [];
				foreach ($model->leaders as $leader) {
					$items[] = BadgeWidget::widget([
						'models' => BadgeWidget::widget([
							'prefix' => BadgeWidget::widget([
									'models' => $leader,
									'useBadges' => false,
									'attribute' => 'username',
									'unbadgedCount' => 3,
									'itemsSeparator' => false
								]).': ',
							'models' => RefUserRoles::getUserRolesInGroup($leader->id, $model->id),
							'attribute' => 'name',
							'useBadges' => true,
							'itemsSeparator' => false,
							"optionsMap" => RefUserRoles::colorStyleOptions()
						]),
						'linkScheme' => [UsersModule::to(['users/groups']), 'id' => $leader->id]
					]);
				}

				return implode('', $items);
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
				$positionTypeData = $model->getGroupPositionTypeData();
				$items[] = BadgeWidget::widget([
					'models' => "Всего: {$model->usersCount}",
					"badgeOptions" => [
						'class' => "badge badge-info pull-left"
					],
					'linkScheme' => ['users', 'UsersSearch[groupId]' => $model->id]

				]);
				foreach ($positionTypeData as $positionId => $positionCount) {
					/** @var RefUserPositionTypes $positionType */
					$positionType = RefUserPositionTypes::findModel($positionId);
					$items[] = BadgeWidget::widget([
						'models' => "{$positionType->name}: $positionCount",
						"badgeOptions" => [
							'style' => "float:left; background: {$positionType->color}; color: $positionType->textcolor"
						],
						'linkScheme' => ['users', 'UsersSearch[positionType]' => $positionId, 'UsersSearch[groupId]' => $model->id]

					]);
				}
				$items[] = BadgeWidget::widget([
					'models' => "Вакансии: {$model->vacancyCount}",
					"badgeOptions" => [
						'class' => "badge badge-danger pull-right"
					],
					'linkScheme' => [VacancyModule::to('groups'), 'id' => $model->id]

				]);

				return implode('', $items);
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
				foreach ($positionTypeData as $positionId => $positionCount) {
					/** @var RefUserPositionTypes $positionType */
					$positionType = RefUserPositionTypes::findModel($positionId);
					$items[] = BadgeWidget::widget([
						'models' => "{$positionType->name}: $positionCount",
						"badgeOptions" => [
							'style' => "float:left; background: {$positionType->color}; color: $positionType->textcolor"
						]
					]);
				}

				$vacancyCount = Groups::getGroupScopeVacancyCount($groupsScope);
				$items[] = BadgeWidget::widget([
					'models' => "Вакансии: {$vacancyCount}",
					"badgeOptions" => [
						'class' => "badge badge-danger pull-right"
					]
				]);

				return implode('', $items);
			}
		]
	]
]) ?>

