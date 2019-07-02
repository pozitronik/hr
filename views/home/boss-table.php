<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var GroupsSearch $searchModel
 * @var ActiveDataProvider $dataProvider
 */

use app\helpers\Utils;
use app\modules\groups\models\Groups;
use app\modules\groups\models\GroupsSearch;
use app\modules\groups\models\references\RefGroupTypes;
use app\modules\references\ReferencesModule;
use app\modules\references\widgets\reference_select\ReferenceSelectWidget;
use app\modules\salary\models\references\RefUserPositionTypes;
use app\modules\users\models\references\RefUserRoles;
use app\modules\users\models\Users;
use app\modules\users\UsersModule;
use app\widgets\badge\BadgeWidget;
use kartik\grid\DataColumn;
use kartik\grid\GridView;
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
	'columns' => [
		[
			'headerOptions' => ['class' => 'text-center'],
			'attribute' => 'name',
			'value' => static function(Groups $model) {
				return Html::a($model->name, ['profile', 'id' => $model->id]);
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
					'data' => $model->relGroupTypes,
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
						'value' => BadgeWidget::widget([
								'data' => $leader,
								'useBadges' => false,
								'attribute' => 'username',
								'unbadgedCount' => 3,
								'itemsSeparator' => false
							]).': '.BadgeWidget::widget([
								'data' => RefUserRoles::getUserRolesInGroup($leader->id, $model->id),
								'attribute' => 'name',
								'useBadges' => true,
								'itemsSeparator' => false,
								"optionsMap" => static function() {
									return RefUserRoles::colorStyleOptions();
								}
							]),
						'linkScheme' => [UsersModule::to(['users/groups']), 'id' => 'id']
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
			'headerOptions' => ['class' => 'text-center'],
			'header' => 'Сотрудники',
			'format' => 'raw',
			'value' => static function(Groups $model) {
				$positionTypeData = $model->getGroupPositionTypeData();
				$items[] = BadgeWidget::widget([
					'value' => "Всего: {$model->usersCount}",
					"badgeOptions" => [
						'class' => "badge badge-info pull-left"
					],
					'linkScheme' => ['users', 'UsersSearch[groupId]' => $model->id]

				]);
				foreach ($positionTypeData as $positionId => $positionCount) {
					/** @var RefUserPositionTypes $positionType */
					$positionType = RefUserPositionTypes::findModel($positionId);
					$items[] = BadgeWidget::widget([
						'value' => "{$positionType->name}: $positionCount",
						"badgeOptions" => [
							'style' => "float:left; background: {$positionType->color}; color: ".Utils::RGBColorContrast($positionType->color)
						],
						'linkScheme' => ['users', 'UsersSearch[positionType]' => $positionId, 'UsersSearch[groupId]' => $model->id]

					]);
				}
				return implode('', $items);
			}
		]
	]
]) ?>

