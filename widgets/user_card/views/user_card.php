<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Users $user
 * @var Groups $group
 */

use app\modules\groups\models\Groups;
use app\modules\salary\models\references\RefUserPositions;
use app\modules\salary\models\references\RefUserPositionTypes;
use app\modules\users\models\references\RefUserRoles;
use app\modules\users\models\Users;
use app\modules\users\UsersModule;
use app\widgets\badge\BadgeWidget;
use yii\web\View;

$badgeData = [];
?>
<?php foreach ($user->relGroups as $userGroup): ?>
	<?php $groupRoles = RefUserRoles::getUserRolesInGroup($user->id, $userGroup->id) ?>
	<?php $badgeData[] = ((empty($groupRoles))?'Сотрудник':BadgeWidget::widget([
			'data' => $groupRoles,
			'attribute' => 'name',
			'itemsSeparator' => false,
			"optionsMap" => static function() {
				return RefUserRoles::colorStyleOptions();
			},
		])).' в '.BadgeWidget::widget([
			'value' => $userGroup->name,
			"badgeOptions" => [
				'class' => "badge badge-info"
			]
		]) ?>
<?php endforeach; ?>

<div class="panel panel-card col-md-3" style="border-left: 7px solid rgb(236, 240, 245);border-right: 7px solid rgb(236, 240, 245);">
	<div class="panel-heading">
		<h3 class="panel-title"><?= $user->username ?>: <?= BadgeWidget::widget([
				'data' => $user->getRefUserPositionTypes()->all(),
				'attribute' => 'name',
				'unbadgedCount' => false,
				'itemsSeparator' => false,
				"optionsMap" => static function() {
					return RefUserPositionTypes::colorStyleOptions();
				},
			]) ?></h3>
	</div>

	<div class="panel-body">
		<div class="row">
			<div class="col-md-12">
				<label>Должность:
					<?= BadgeWidget::widget([
						'data' => $user->relRefUserPositions,
						'attribute' => 'name',
						'unbadgedCount' => false,
						'itemsSeparator' => false,
						"optionsMap" => static function() {
							return RefUserPositions::colorStyleOptions();
						},
					]) ?>
				</label>
			</div>
		</div>
		<div class="list-divider"></div>
		<div class="row">
			<div class="col-md-12">
				<label>Роли:
					<?php foreach ($badgeData as $badgeString): ?>
						<?= BadgeWidget::widget([
							'value' => $badgeString,
							"badgeOptions" => [
								'class' => "badge",
								'style' => 'margin-bottom:1px'
							]
						]) ?>
					<?php endforeach; ?>
				</label>
			</div>

		</div>
		<div class="list-divider"></div>
		<div class="row">
			<div class="col-md-12">
				<label>В подчинении у:
					<?= BadgeWidget::widget([
						'data' => $user->getBosses(),
						'attribute' => 'username',
						'unbadgedCount' => false,
						'itemsSeparator' => false,
						'linkScheme' => [UsersModule::to('users/profile'), 'id' => 'id']
					]) ?>
				</label>
			</div>
		</div>

	</div>
</div>
