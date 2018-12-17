<?php
declare(strict_types = 1);

/* @var View $this
 * @var Users $model
 * @var Groups $group
 * @var boolean $boss
 * @var array $options
 */

use app\models\groups\Groups;
use app\models\references\refs\RefUserRoles;
use app\models\relations\RelUsersGroupsRoles;
use app\models\users\Users;
use app\widgets\badge\BadgeWidget;
use yii\helpers\Html;
use yii\web\View;

?>

<div class="fixed-fluid pull-left">
	<div class="pull-sm-left">
		<div class="panel user">
			<?php if ($group->isLeader($model)): ?>
				<div class="crown text-center pull-left">
					<?= Html::a(Html::img($model->avatar, ['class' => 'img-sm img-border img-circle', 'alt' => $model->username]), ['admin/users/update', 'id' => $model->id]); ?>
				</div>

			<?php else: ?>
				<div class="avatar text-center pull-left">
					<?= Html::a(Html::img($model->avatar, ['class' => 'img-sm img-border img-circle', 'alt' => $model->username]), ['admin/users/update', 'id' => $model->id]); ?>
				</div>
			<?php endif; ?>


			<div class="mar-btm pull-left">
				<span class="text-semibold text-main"><?= Html::a($model->username, ['admin/users/update', 'id' => $model->id]); ?></span>
				<p class="text-xs text-right"><?= $model->positionName; ?></p>
				<span>
					<?= BadgeWidget::widget([
						"data" => RefUserRoles::findModels(RelUsersGroupsRoles::getRoleIdInGroup($model->id, $group->id)),
						"attribute" => 'name',
						"unbadgedCount" => 5,
						"useBadges" => true,
						"itemsSeparator" => false,
						"optionsMap" => $options
					]); ?>
				</span>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
</div>
