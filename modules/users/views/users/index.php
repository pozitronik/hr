<?php
declare(strict_types = 1);

/**
 * Шаблон главной страницы списка всех пользователей
 * @var View $this
 * @var UsersSearch $searchModel
 * @var ActiveDataProvider $dataProvider
 * @var Groups[] $groupsScope Группы, в скоупе которых проводился поиск (не разрешённые, а выбранные)
 * @deprecated
 */

use app\modules\groups\models\Groups;
use app\modules\groups\models\references\RefGroupTypes;
use app\modules\salary\models\references\RefUserPositionTypes;
use app\modules\users\UsersModule;
use app\components\pozitronik\helpers\ArrayHelper;
use app\models\core\IconsHelper;
use app\components\pozitronik\helpers\Utils;
use app\modules\salary\models\references\RefUserPositions;
use app\modules\users\models\references\RefUserRoles;
use app\modules\privileges\models\Privileges;
use app\components\pozitronik\references\widgets\reference_select\ReferenceSelectWidget;
use app\modules\users\models\Users;
use app\modules\users\models\UsersSearch;
use app\modules\users\widgets\navigation_menu\UserNavigationMenuWidget;
use app\components\pozitronik\badgewidget\BadgeWidget;
use kartik\grid\DataColumn;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\View;
use kartik\grid\GridView;
use yii\bootstrap\Html;

if (null !== $searchModel) {//Учитываем вызов из поиска по атрибутам, пока используется одна вьюха на всё. todo разные вьюхи
	$this->title = 'Люди';
	$this->params['breadcrumbs'][] = $this->title;
}
?>

<?= GridView::widget([
	'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
	'panel' => [
		'heading' => $this->title.(($dataProvider->totalCount > 0)?" (".Utils::pluralForm($dataProvider->totalCount, ['пользователь', 'пользователя', 'пользователей']).")":" (нет пользователей)"),
		'before' => ([] === $groupsScope)?false:BadgeWidget::widget([
				'models' => $groupsScope,
				'useBadges' => true,
				'attribute' => 'name',
				'unbadgedCount' => false,
				'itemsSeparator' => false,
				"optionsMap" => RefGroupTypes::colorStyleOptions(),
				"optionsMapAttribute" => 'type',
				'prefix' => 'Ищем в группах: '
			]).Html::a('Очистить', UsersModule::to(), ['class' => 'btn btn-xs btn-info pull-right'])
	],
	'summary' => null !== $searchModel?Html::a('Новый пользователь', UsersModule::to(['users/create']), ['class' => 'btn btn-success summary-content']):null,
	'showOnEmpty' => true,
	'emptyText' => Html::a('Новый пользователь', UsersModule::to(['users/create']), ['class' => 'btn btn-success']),
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
			'value' => static function(Users $model) {
				return UserNavigationMenuWidget::widget([
					'model' => $model,
					'mode' => UserNavigationMenuWidget::MODE_ACTION_COLUMN_MENU
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
					'linkScheme' => [Url::current(['UsersSearch[positions]' => $model->position])]
				]);
			},
			'filter' => ArrayHelper::getValue($searchModel, 'positions'),
			'filterType' => ReferenceSelectWidget::class,
			'filterInputOptions' => ['placeholder' => 'Выберите должность'],
			'filterWidgetOptions' => [
				'referenceClass' => RefUserPositions::class,
				'pluginOptions' => ['allowClear' => true, 'multiple' => true]
			],
			'format' => 'raw'
		],
		[
			'class' => DataColumn::class,
			'attribute' => 'positionType',
			'label' => 'Тип должности',
			'value' => static function(Users $model) {
				return BadgeWidget::widget([
					'models' => $model->relRefUserPositionsTypesAny,
					'useBadges' => true,
					'attribute' => 'name',
					'unbadgedCount' => false,
					'itemsSeparator' => false,
					"optionsMap" => RefUserPositionTypes::colorStyleOptions(),
					'linkScheme' => ['', 'UsersSearch[positionType]' => 'id']
				]);
			},
			'filter' => ArrayHelper::getValue($searchModel, 'positionType'),
			'filterType' => ReferenceSelectWidget::class,
			'filterInputOptions' => ['placeholder' => 'Выберите тип'],
			'filterWidgetOptions' => [
				'referenceClass' => RefUserPositionTypes::class,
				'pluginOptions' => ['allowClear' => true, 'multiple' => true]
			],
			'format' => 'raw'
		],
		[
			'attribute' => 'groupName',
			'label' => 'Группы',
			'value' => static function(Users $model) {
				return BadgeWidget::widget([
					'models' => $model->relGroups,
					'useBadges' => true,
					'attribute' => 'name',
					'itemsSeparator' => false,
					"optionsMap" => RefGroupTypes::colorStyleOptions(),
					"optionsMapAttribute" => 'type',
					'unbadgedCount' => false,
					'linkScheme' => ['/home/users', 'UsersSearch[groupId]' => 'id']
				]);
			},
			'format' => 'raw'
		],
		[
			'class' => DataColumn::class,
			'attribute' => 'roles',
			'filter' => ArrayHelper::getValue($searchModel, 'relRefUserRoles'),
			'filterType' => ReferenceSelectWidget::class,
			'filterInputOptions' => ['placeholder' => 'Выберите роль'],
			'filterWidgetOptions' => [
				'referenceClass' => RefUserRoles::class,
				'pluginOptions' => ['allowClear' => true, 'multiple' => true]
			],
			'label' => 'Роли',
			'value' => static function(Users $model) {
				return BadgeWidget::widget([
					'models' => $model->getRelRefUserRoles()->all(),//здесь нельзя использовать свойство, т.к. фреймворк не подгружает все релейшены в $_related сразу. Выяснено экспериментально, на более подробные разбирательства нет времени
					'useBadges' => true,
					'attribute' => 'name',
					'unbadgedCount' => false,
					"itemsSeparator" => false,
					"optionsMap" => RefUserRoles::colorStyleOptions(),
					'linkScheme' => ['', 'UsersSearch[roles]' => 'id']
				]);
			},
			'format' => 'raw'
		],
		[
			'class' => DataColumn::class,
			'attribute' => 'privileges',
			'filterType' => GridView::FILTER_SELECT2,
			'filter' => ArrayHelper::map(Privileges::find()->active()->all(), 'id', 'name'),
			'filterInputOptions' => ['placeholder' => 'Выберите привилегии'],
			'filterWidgetOptions' => ['pluginOptions' => ['allowClear' => true, 'multiple' => true]],

			'label' => 'Привилегии',
			'value' => static function(Users $model) {
				return BadgeWidget::widget([
					'models' => $model->getRelPrivileges()->all(),//здесь нельзя использовать свойство, т.к. фреймворк не подгружает все релейшены в $_related сразу. Выяснено экспериментально, на более подробные разбирательства нет времени
					'useBadges' => true,
					'attribute' => 'name',
					'unbadgedCount' => 6,
					"itemsSeparator" => false
				]);
			},
			'format' => 'raw'
		]

	]
]) ?>