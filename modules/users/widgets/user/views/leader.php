<?php
declare(strict_types = 1);

/* @var View $this
 * @var Users $model
 * @var Groups $group
 * @var boolean $boss
 * @var array|callable $options
 */

use app\modules\groups\models\Groups;
use app\modules\users\models\references\RefUserRoles;
use app\models\relations\RelUsersGroupsRoles;
use app\models\user\CurrentUser;
use app\modules\users\models\Users;
use app\widgets\badge\BadgeWidget;
use yii\helpers\Html;
use yii\web\View;

$borderStyle = (CurrentUser::Id() === $model->id)?'img-border-current':'img-border';

?>

<div class="fixed-fluid pull-left">
	<div class="pull-sm-left">
		<div class="panel user">
			<?php if ($group->isLeader($model)): ?>
				<div class="crown text-center pull-left">
					<?= Html::a(Html::img($model->avatar, ['class' => "img-sm {$borderStyle} img-circle", 'alt' => $model->username]), ['/users/users/profile', 'id' => $model->id]); ?>
				</div>

			<?php else: ?>
				<div class="avatar text-center pull-left">
					<?= Html::a(Html::img($model->avatar, ['class' => "img-sm {$borderStyle} img-circle", 'alt' => $model->username]), ['/users/users/profile', 'id' => $model->id]); ?>
				</div>
			<?php endif; ?>


			<div class="mar-btm pull-left">
				<span class="text-semibold text-main"><?= Html::a($model->username, ['/users/users/profile', 'id' => $model->id]); ?></span>
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
