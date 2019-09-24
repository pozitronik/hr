<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Users $model
 * @var ActiveDataProvider $provider
 */

use app\helpers\Utils;
use app\modules\groups\models\Groups;
use app\modules\groups\models\references\RefGroupTypes;
use app\modules\home\HomeModule;
use app\modules\users\models\references\RefUserRoles;
use app\modules\users\models\Users;
use app\modules\users\UsersModule;
use app\widgets\badge\BadgeWidget;
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