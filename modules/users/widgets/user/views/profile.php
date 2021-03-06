<?php
declare(strict_types = 1);

/* @var View $this
 * @var Users $model
 * @var bool $boss
 */

use app\models\user\CurrentUser;
use app\modules\users\models\Users;
use yii\web\View;
use yii\helpers\Html;
$borderStyle = (CurrentUser::Id() === $model->id)?'img-border-current':'img-border';
?>
<div class="container-fluid">
	<div class="pull-sm-left">
		<div class="panel">
			<div class="text-center pad-all bord-btm">
				<div class="pad-ver">
					<?= Users::a(Html::img($model->avatar, ['class' => "img-lg {$borderStyle} img-circle", 'alt' => $model->username]), ['users/profile', 'id' => $model->id]) ?>
				</div>

				<h4 class="text-lg mar-no" style="white-space: nowrap;"><?= Users::a($model->username, ['users/profile', 'id' => $model->id]) ?></h4>
			</div>
			<div class="mar-btm">
				<p class="text-semibold text-main pad-all mar-no text-lg-center"><?= $model->positionName ?></p>
			</div>
		</div>
	</div>
</div>