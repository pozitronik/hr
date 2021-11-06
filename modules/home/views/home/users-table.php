<?php
declare(strict_types = 1);

/**
 * Шаблон главной страницы списка всех пользователей
 * @var View $this
 * @var UsersSearch $searchModel
 * @var ActiveDataProvider $dataProvider
 * @var Groups $group
 */

use app\modules\groups\models\Groups;
use app\modules\groups\widgets\group_leaders\GroupLeadersWidget;
use app\modules\groups\widgets\group_users\GroupUsersWidget;
use app\modules\home\HomeModule;
use app\modules\salary\models\references\RefUserPositionTypes;
use app\modules\users\UsersModule;
use app\components\pozitronik\helpers\ArrayHelper;
use app\modules\salary\models\references\RefUserPositions;
use app\modules\users\models\references\RefUserRoles;
use app\components\pozitronik\references\widgets\reference_select\ReferenceSelectWidget;
use app\modules\users\models\Users;
use app\modules\users\models\UsersSearch;
use app\components\pozitronik\widgets\BadgeWidget;
use kartik\grid\DataColumn;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\View;
use kartik\grid\GridView;
use yii\bootstrap\Html;

$this->title = "Сводка по сотрудникам {$group->name}";
$this->params['breadcrumbs'][] = $this->title;
?>

<?= GridView::widget([
	'containerOptions' => [
		'style' => 'overflow-x:inherit'//убираем скроллбар нахер
	],
	'dataProvider' => $dataProvider,
	'filterModel' => $searchModel,
	'panel' => [
		'heading' => $this->title,
		'before' => GroupUsersWidget::widget(['group' => $group]).GroupLeadersWidget::widget(['group' => $group])
	],
	'summary' => false,
	'showOnEmpty' => true,
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
					'linkScheme' => [Url::current(['UsersSearch[positions]' => $model->position])]
				]);
			},
			'filter' => ArrayHelper::getValue($searchModel, 'positions'),
			'filterInputOptions' => ['placeholder' => 'Выберите должность'],
			'filterType' => ReferenceSelectWidget::class,
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
			'value' => static function(Users $model) use ($searchModel) {
				return BadgeWidget::widget([
					'models' => $model->relRefUserPositionsTypesAny,
					'useBadges' => true,
					'attribute' => 'name',
					'unbadgedCount' => 3,
					'itemsSeparator' => false,
					"optionsMap" => RefUserPositionTypes::colorStyleOptions(),
					'linkScheme' => ['', 'UsersSearch[groupId]' => $searchModel->groupId, 'UsersSearch[positionType][]' => 'id']
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
			'class' => DataColumn::class,
			'attribute' => 'roles',
			'label' => 'Роли в группах',
			'filter' => ArrayHelper::getValue($searchModel, 'relRefUserRoles'),
			'filterType' => ReferenceSelectWidget::class,
			'filterInputOptions' => ['placeholder' => 'Выберите роль'],
			'filterWidgetOptions' => [
				'referenceClass' => RefUserRoles::class,
				'pluginOptions' => ['allowClear' => true, 'multiple' => true]
			],
			'value' => static function(Users $model) {
				return BadgeWidget::widget([
					'models' => BadgeWidget::widget([
						'models' => static function() use ($model) {
							$badgeData = [];
							/** @var Groups $userGroup */
							foreach ((array)$model->relGroups as $userGroup) {
								$badgeData[] = BadgeWidget::widget([
										'models' => RefUserRoles::getUserRolesInGroup($model->id, $userGroup->id),
										'attribute' => 'name',
										'itemsSeparator' => false,
										"optionsMap" => RefUserRoles::colorStyleOptions(),
										'emptyResult' => 'Сотрудник'
									]).' в '.BadgeWidget::widget([
										'models' => $userGroup->name,
										"badgeOptions" => [
											'class' => "badge badge-info inline-block"
										],
										'linkScheme' => [HomeModule::to(['/home/users', 'UsersSearch[groupId]' => $userGroup->id, 't' => 1])]
									]);
							}
							return $badgeData;
						},
						'unbadgedCount' => false,
						'itemsSeparator' => false,
						"badgeOptions" => [
							'class' => "badge inline-block",
							'style' => 'margin:1px 0px 1px 0px; float:left;'
						]
					]),
					'useBadges' => true,
					'unbadgedCount' => false,
					'itemsSeparator' => false,
					'badgeOptions' => [
						'class' => 'badge inline-block',
						'style' => ArrayHelper::getValue($model->relRefUserPositionsTypesAny,'0.style','background:transparent')
					]
				]);
			},
			'format' => 'raw'
		],
		[
			'label' => 'Подчинение',
			'class' => DataColumn::class,
			'attribute' => 'subordination',
			'value' => static function(Users $model) {
				return BadgeWidget::widget([
					'models' => $model->getBosses(),
					'attribute' => 'username',
					'unbadgedCount' => false,
					'itemsSeparator' => false,
					'linkScheme' => [UsersModule::to('users/profile'), 'id' => 'id']
				]);
			},
			'format' => 'raw'
		]

	]
]) ?>