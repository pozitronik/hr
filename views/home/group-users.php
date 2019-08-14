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
use app\modules\salary\models\references\RefUserPositionTypes;
use app\modules\users\UsersModule;
use app\widgets\group_card\GroupCardWidget;
use pozitronik\helpers\ArrayHelper;
use app\modules\salary\models\references\RefUserPositions;
use app\modules\users\models\references\RefUserRoles;
use app\modules\references\widgets\reference_select\ReferenceSelectWidget;
use app\modules\users\models\Users;
use app\modules\users\models\UsersSearch;
use app\widgets\badge\BadgeWidget;
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
		'before' => GroupCardWidget::widget(['group' => $group, 'short' => true])
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
			'value' => static function(Users $model) use ($searchModel) {
				return BadgeWidget::widget([
					'models' => $model->getRefUserPositionTypes()->all(),/*Именно так, иначе мы напоремся на отсечку атрибутов дистинктом (вспомни, как копали с Ваней)*/
					'useBadges' => true,
					'attribute' => 'name',
					'unbadgedCount' => 3,
					'itemsSeparator' => false,
					"optionsMap" => static function() {
						return RefUserPositionTypes::colorStyleOptions();
					},
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
								$groupRoles = RefUserRoles::getUserRolesInGroup($model->id, $userGroup->id);
								$badgeData[] = (empty($groupRoles)?'Сотрудник':BadgeWidget::widget([
										'models' => $groupRoles,
										'attribute' => 'name',
										'itemsSeparator' => false,
										"optionsMap" => static function() {
											return RefUserRoles::colorStyleOptions();
										}
									])).' в '.BadgeWidget::widget([
										'models' => $userGroup->name,
										"badgeOptions" => [
											'class' => "badge badge-info"
										],
										'linkScheme' => ['/home/users', 'UsersSearch[groupId]' => $userGroup->id, 't' => 1]
									]);
							}
							return $badgeData;
						},
						'unbadgedCount' => false,
						'itemsSeparator' => false,
						"badgeOptions" => [
							'class' => "badge",
							'style' => 'margin:1px 0px 1px 0px; float:left;'
						]
					]),
					'useBadges' => true,
					'unbadgedCount' => false,
					'itemsSeparator' => false,
					"badgeOptions" => ArrayHelper::getValue(RefUserPositionTypes::colorStyleOptions(), ArrayHelper::getValue($model->relRefUserPositions, 'types'), [])//Не сработает, если у пользователя несколько типов должностей. Это запрещено логически, но доступно технически
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