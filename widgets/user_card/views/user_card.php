<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Users $user
 * @var Groups $group
 */

use app\modules\groups\models\Groups;
use app\modules\users\models\references\RefUserRoles;
use app\modules\users\models\Users;
use app\widgets\badge\BadgeWidget;
use yii\web\View;

$badgeData = [];
?>


<div class="panel panel-card col-md-3" style="border-left: 7px solid rgb(236, 240, 245);border-right: 7px solid rgb(236, 240, 245);">
	<div class="panel-heading">
		<div class="panel-control">
		</div>
		<h3 class="panel-title"><?= $user->username ?></h3>
	</div>

	<div class="panel-body">
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
		<div class="row">
			<div class="col-md-12">
				<?php foreach ($badgeData as $badgeString): ?>
					<?= BadgeWidget::widget([
						'value' => $badgeString,
						"badgeOptions" => [
							'class' => "badge"
						]
					]) ?>
				<?php endforeach; ?>


			</div>

		</div>


	</div>
	<div class="panel-footer">

	</div>
</div>
