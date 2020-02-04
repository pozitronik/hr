<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Groups $model
 * @var ActiveDataProvider $provider
 * @var bool $showUserSelector Отображать виджет выбора пользователя
 * @var bool $showRolesSelector Отображать колонку выбиралки роли для пользователя (отключаем в некоторых случаях для ускорения)
 * @var bool $showDropColumn Отображать колонку удаления пользюков
 *
 * @var bool|string $heading
 */

use app\helpers\Utils;
use app\modules\groups\GroupsModule;
use app\modules\groups\models\Groups;
use app\modules\groups\models\references\RefGroupTypes;
use app\modules\salary\models\references\RefUserPositions;
use app\modules\salary\models\references\RefUserPositionTypes;
use app\modules\users\models\references\RefUserRoles;
use app\modules\users\models\Users;
use app\modules\users\UsersModule;
use app\widgets\badge\BadgeWidget;
use kartik\grid\DataColumn;
use kartik\grid\GridView;
use pozitronik\helpers\ArrayHelper;
use yii\bootstrap\Html;
use yii\data\ActiveDataProvider;
use yii\web\View;

?>

<?= GridView::widget([
	'dataProvider' => $provider,
	'summary' => Html::a('Редактор', GroupsModule::to(['groups/users', 'id' => $model->id]), ['class' => 'btn btn-success summary-content']),
	'panel' => [
		'heading' => 'Сотрудники'.(($provider->totalCount > 0)?" (".Utils::pluralForm($provider->totalCount, ['сотрудник', 'сотрудника', 'сотрудников']).")":" (нет)"),
		'footer' => false
	],
	'toolbar' => false,
	'export' => false,
	'resizableColumns' => true,
	'responsive' => true,
	'columns' => [
		[
			'value' => static function(Users $model) {
				return Html::img($model->avatar, ['class' => 'img-circle img-xs']);
			},
			'label' => 'Аватар',
			'format' => 'raw',
			'contentOptions' => ['class' => 'text-center'],
			'options' => [
				'style' => 'width: 40px;'
			]
		],
		[
			'attribute' => 'username',
			'value' => static function(Users $model) {
				return Users::a($model->username, ['users/profile', 'id' => $model->id]);
			},
			'format' => 'raw'
		],
		[
			'class' => DataColumn::class,
			'label' => 'Должность',
			'attribute' => 'positions',
			'value' => static function(Users $model) {
				return BadgeWidget::widget([
					'models' => $model->relRefUserPositions,
					'useBadges' => true,
					'attribute' => 'name',
					'unbadgedCount' => 3,
					'itemsSeparator' => false,
					"optionsMap" => RefUserPositions::colorStyleOptions(),
					'linkScheme' => [UsersModule::to(), 'UsersSearch[positions]' => $model->position, 'UsersSearch[groupId]' => ArrayHelper::getColumn($model->relGroups, 'id', [])]
				]);
			},
			'format' => 'raw'
		],
		[
			'class' => DataColumn::class,
			'attribute' => 'positionType',
			'label' => 'Тип должности',
			'value' => static function(Users $user) {
				return BadgeWidget::widget([
					'models' => $user->relRefUserPositionsTypesAny,
					'useBadges' => true,
					'attribute' => 'name',
					'unbadgedCount' => 3,
					'itemsSeparator' => false,
					"optionsMap" => RefUserPositionTypes::colorStyleOptions(),
					'linkScheme' => [UsersModule::to(), 'UsersSearch[positionType]' => 'id', 'UsersSearch[groupId]' => ArrayHelper::getColumn($user->relGroups, 'id', [])]
				]);
			},
			'format' => 'raw'
		],
		[
			'class' => DataColumn::class,
			'attribute' => 'roles',
			'label' => 'Роль в группе',
			'value' => static function(Users $user) use ($model) {
				return BadgeWidget::widget([
					'models' => RefUserRoles::getUserRolesInGroup($user->id, $model->id),
					'attribute' => 'name',
					'itemsSeparator' => false,
					"optionsMap" => RefUserRoles::colorStyleOptions(),
					'emptyResult' => 'Сотрудник'
				]);
			},
			'format' => 'raw'
		],
		[
			'class' => DataColumn::class,
			'label' => 'Другие группы',
			'value' => static function(Users $user) use ($model) {
				$otherGroups = $user->getRelGroups()->andWhere(['<>', 'id', $model->id])->active()->all();
				return BadgeWidget::widget([
					'models' => $otherGroups,
					'attribute' => 'name',
					'itemsSeparator' => '<br />',
					"optionsMap" => RefGroupTypes::colorStyleOptions(),
					"optionsMapAttribute" => 'type',
					'linkScheme' => ['/groups/groups/profile', 'id' => 'id'],
					'emptyResult' => null
				]);
			},
			'format' => 'raw'
		]

	]
]) ?>
