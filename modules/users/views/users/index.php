<?php
declare(strict_types = 1);

/**
 * Шаблон главной страницы списка всех пользователей
 * @var View $this
 * @var UsersSearch $searchModel
 * @var ActiveDataProvider $dataProvider
 * @deprecated
 */

use app\helpers\ArrayHelper;
use app\helpers\Icons;
use app\helpers\Utils;
use app\modules\salary\models\references\RefUserPositions;
use app\modules\users\models\references\RefUserRoles;
use app\modules\privileges\models\Privileges;
use app\modules\references\widgets\reference_select\ReferenceSelectWidget;
use app\modules\users\models\Users;
use app\modules\users\models\UsersSearch;
use app\modules\users\widgets\navigation_menu\UserNavigationMenuWidget;
use app\widgets\badge\BadgeWidget;
use yii\data\ActiveDataProvider;
use yii\web\View;
use kartik\grid\GridView;
use yii\bootstrap\Html;

if (null !== $searchModel) {//Учитываем вызов из поиска по атрибутам, пока используется одна вьюха на всё.
	$this->title = 'Люди';
	$this->params['breadcrumbs'][] = $this->title;
}

?>

<?= /** @noinspection MissedFieldInspection */
GridView::widget([
	'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
	'panel' => [
		'heading' => $this->title.(($dataProvider->totalCount > 0)?" (".Utils::pluralForm($dataProvider->totalCount, ['пользователь', 'пользователя', 'пользователей']).")":" (нет пользователей)")
	],
	'summary' => null !== $searchModel?Html::a('Новый пользователь', '/users/users/create', ['class' => 'btn btn-success summary-content']):null,
	'showOnEmpty' => true,
	'emptyText' => Html::a('Новый пользователь', 'create', ['class' => 'btn btn-success']),
	'toolbar' => false,
	'export' => false,
	'resizableColumns' => true,
	'responsive' => true,
	'columns' => [
		[
			'filter' => false,
			'header' => Icons::menu(),
			'mergeHeader' => true,
			'headerOptions' => [
				'class' => 'skip-export kv-align-center kv-align-middle'
			],
			'contentOptions' => [
				'style' => 'width:50px',
				'class' => 'skip-export kv-align-center kv-align-middle'
			],
			'value' => function(Users $model) {
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
			'value' => function(Users $model) {
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
			'value' => function(Users $model) {
				return Html::a($model->username, ['/users/users/profile', 'id' => $model->id]);
			},
			'format' => 'raw'
		],
		[
			'label' => 'Должность',
			'attribute' => 'positions',
			'value' => 'positionName',
			'filter' => ArrayHelper::getValue($searchModel, 'positions'),
			'filterType' => ReferenceSelectWidget::class,
			'filterInputOptions' => ['placeholder' => 'Выберите должность'],
			'filterWidgetOptions' => [
				'referenceClass' => RefUserPositions::class,
				'pluginOptions' => ['allowClear' => true, 'multiple' => true]
			]
		],
		[
			'attribute' => 'groupName',
			'label' => 'Группы',
			'value' => function(Users $model) {
				return BadgeWidget::widget([
					'data' => $model->relGroups,
					'useBadges' => false,
					'attribute' => 'name',
					'linkScheme' => ['/groups/groups/profile', 'id' => 'id']
				]);
			},
			'format' => 'raw'
		],
		[
			'attribute' => 'roles',
			'filter' => ArrayHelper::getValue($searchModel, 'relRefUserRoles'),
			'filterType' => ReferenceSelectWidget::class,
			'filterInputOptions' => ['placeholder' => 'Выберите роль'],
			'filterWidgetOptions' => [
				'referenceClass' => RefUserRoles::class,
				'pluginOptions' => ['allowClear' => true, 'multiple' => true]
			],

			'label' => 'Роли',
			'value' => function(Users $model) {
				return BadgeWidget::widget([
					'data' => $model->getRelRefUserRoles()->all(),//здесь нельзя использовать свойство, т.к. фреймворк не подгружает все релейшены в $_related сразу. Выяснено экспериментально, на более подробные разбирательства нет времени
					'useBadges' => true,
					'attribute' => 'name',
					'unbadgedCount' => 6,
					"itemsSeparator" => false,
					"optionsMap" => function() {
						$options = ArrayHelper::map(RefUserRoles::find()->active()->all(), 'id', 'color');
						array_walk($options, function(&$value, $key) {
							if (!empty($value)) {
								$value = [
									'style' => "background: $value;"
								];
							}
						});
						return $options;
					}
				]);
			},
			'format' => 'raw'
		],
		[
			'attribute' => 'privileges',
			'filterType' => GridView::FILTER_SELECT2,
			'filter' => ArrayHelper::map(Privileges::find()->active()->all(), 'id', 'name'),
			'filterInputOptions' => ['placeholder' => 'Выберите привилегии'],
			'filterWidgetOptions' => ['pluginOptions' => ['allowClear' => true, 'multiple' => true]],

			'label' => 'Привилегии',
			'value' => function(Users $model) {
				return BadgeWidget::widget([
					'data' => $model->getRelPrivileges()->all(),//здесь нельзя использовать свойство, т.к. фреймворк не подгружает все релейшены в $_related сразу. Выяснено экспериментально, на более подробные разбирательства нет времени
					'useBadges' => true,
					'attribute' => 'name',
					'unbadgedCount' => 6,
					"itemsSeparator" => false
				]);
			},
			'format' => 'raw'
		]

	]
]); ?>