<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Users $model
 * @var ActiveDataProvider $provider
 */

use app\components\pozitronik\helpers\Utils;
use app\modules\groups\GroupsModule;
use app\modules\groups\models\Groups;
use app\modules\groups\models\references\RefGroupTypes;
use app\modules\home\HomeModule;
use app\components\pozitronik\references\widgets\reference_select\ReferenceSelectWidget;
use app\modules\users\models\references\RefUserRoles;
use app\modules\users\models\Users;
use app\modules\users\UsersModule;
use app\components\pozitronik\badgewidget\BadgeWidget;
use kartik\grid\DataColumn;
use yii\bootstrap\ButtonGroup;
use yii\bootstrap\Html;
use yii\data\ActiveDataProvider;
use yii\web\View;
use kartik\grid\GridView;

?>
<?= GridView::widget([
	'dataProvider' => $provider,
	'showFooter' => false,
	'showPageSummary' => false,
	'summary' => ButtonGroup::widget([
		'buttons' => [
			Html::a('Дашборд', HomeModule::to(['/home', 'u' => $model->id]), ['class' => 'btn btn-info summary-content']),
			Html::a('Редактор', UsersModule::to(['users/groups', 'id' => $model->id]), ['class' => 'btn btn-success summary-content'])
		]
	]),
	'panel' => [
		'heading' => 'Группы'.(($provider->totalCount > 0)?" (".Utils::pluralForm($provider->totalCount, ['группа', 'группы', 'групп']).")":" (нет)"),
		'footer' => false
	],
	'toolbar' => false,
	'export' => false,
	'resizableColumns' => true,
	'responsive' => true,
	'columns' => [
		[
			'format' => 'raw',
			'attribute' => 'name',
			'label' => 'Группа',
			'value' => static function(Groups $group) {
				return BadgeWidget::widget([
					'models' => $group,
					'attribute' => 'name',
					"badgeOptions" => [
						'class' => "badge badge-info"
					],
					"optionsMap" => RefGroupTypes::colorStyleOptions(),
					"optionsMapAttribute" => 'type',
					'linkScheme' => [HomeModule::to(['home/users', 'UsersSearch[groupId]' => $group->id])]
				]);
			}
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
			'label' => 'Роли в группе',
			'value' => static function(Groups $group) use ($model) {
				return BadgeWidget::widget([
					'models' => RefUserRoles::getUserRolesInGroup($model->id, $group->id),
					'attribute' => 'name',
					'itemsSeparator' => false,
					"optionsMap" => RefUserRoles::colorStyleOptions(),
					'emptyResult' => 'Сотрудник',
					'linkScheme' => [UsersModule::to(), 'UsersSearch[roles]' => 'id']
				]);
			},
			'format' => 'raw'
		]
	]

]) ?>