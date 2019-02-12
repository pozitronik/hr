<?php
declare(strict_types = 1);

/* @var View $this
 * @var Users $model
 * @var boolean $boss
 */

use app\models\user\CurrentUser;
use app\modules\users\models\Users;
use yii\web\View;
use yii\helpers\Html;
$borderStyle = (CurrentUser::Id() === $model->id)?'img-border-current':'img-border';
?>
<div class="fixed-fluid pull-left">
	<div class="pull-sm-left">
		<div class="panel">
			<div class="text-center pad-all bord-btm">
				<div class="pad-ver">
					<?= Html::a(Html::img($model->avatar, ['class' => "img-sm {$borderStyle} img-circle", 'alt' => $model->username]), ['/users/users/profile', 'id' => $model->id]); ?>
				</div>

				<h4 class="text-lg mar-no" style="white-space: nowrap;"><?= Html::a($model->username, ['/users/users/profile', 'id' => $model->id]) ?></h4>
			</div>
			<div class="mar-btm">
				<p class="text-semibold text-main pad-all mar-no"><?= $model->positionName; ?></p>
			</div>
		</div>
	</div>
</div>