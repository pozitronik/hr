<?php
declare(strict_types = 1);

/**
 * @var Users $model
 * @var View $this
 * @var ActiveDataProvider $dataProvider
 */

use app\modules\groups\models\Groups;
use app\modules\references\ReferencesModule;
use app\modules\salary\models\references\RefUserPositionTypes;
use app\modules\users\models\references\RefUserRoles;
use app\modules\users\models\Users;
use app\modules\users\UsersModule;
use app\modules\users\widgets\navigation_menu\UserNavigationMenuWidget;
use app\widgets\badge\BadgeWidget;
use kartik\grid\DataColumn;
use kartik\grid\GridView;
use pozitronik\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use yii\web\View;
use yii\helpers\Html;

$this->title = "Профиль пользователя {$model->username}";
$this->params['breadcrumbs'][] = UsersModule::breadcrumbItem('Люди');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="panel panel-default profile-panel">
	<div class="panel-heading">
		<div class="panel-control">
			<?= UserNavigationMenuWidget::widget([
				'model' => $model
			]) ?>
		</div>
		<?= Html::img($model->avatar, ['class' => 'profile-avatar']); ?>
		<h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
	</div>

	<div class="panel-body">
		<div class="row">
			<div class="col-md-12">
				<?= GridView::widget([
					'dataProvider' => $dataProvider,
					'filterModel' => false,
					'panel' => false,
					'summary' => false,
					'showOnEmpty' => true,
					'toolbar' => false,
					'export' => false,
					'resizableColumns' => true,
					'responsive' => true,
					'showHeader' => false,
					'columns' => [
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
									"optionsMap" => static function() {
										return RefUserPositionTypes::colorStyleOptions();
									},
									'linkScheme' => [ReferencesModule::to(['references/update']), 'id' => 'id', 'class' => 'RefUserPositions']
								]);
							},
							'format' => 'raw',
						],
						[
							'class' => DataColumn::class,
							'attribute' => 'positionType',
							'label' => 'Тип должности',
							'value' => static function(Users $model) {
								return BadgeWidget::widget([
									'models' => $model->getRefUserPositionTypes()->all(),/*Именно так, иначе мы напоремся на отсечку атрибутов дистинктом (вспомни, как копали с Ваней)*/
									'useBadges' => true,
									'attribute' => 'name',
									'unbadgedCount' => 3,
									'itemsSeparator' => false,
									"optionsMap" => static function() {
										return RefUserPositionTypes::colorStyleOptions();
									}
								]);
							},
							'format' => 'raw'
						],
						[
							'class' => DataColumn::class,
							'attribute' => 'roles',
							'label' => 'Роли в группах',
							'value' => static function(Users $model) {
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
								$result = '';
								foreach ($badgeData as $badgeString) {
									$result .= BadgeWidget::widget([
										'models' => $badgeString,
										"badgeOptions" => [
											'class' => "badge",
											'style' => 'margin-bottom:1px'
										]
									]);
								}
								return BadgeWidget::widget([
									'models' => $result,
									'useBadges' => true,
									'itemsSeparator' => false,
									"badgeOptions" => ArrayHelper::getValue(RefUserPositionTypes::colorStyleOptions(), $model->relRefUserPositions->types, [])//Не сработает, если у пользователя несколько типов должностей. Это запрещено логически, но доступно технически
								]);
							},
							'format' => 'raw',
						],
						[
							'class' => DataColumn::class,
							'label' => 'Почта',
							'attribute' => 'email',
							'format' => 'email',
						],
					]
				]) ?>
			</div>
		</div>

	</div>

	<div class="panel-footer">
	</div>
</div>