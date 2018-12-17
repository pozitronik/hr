<?php
declare(strict_types = 1);

/* @var View $this
 * @var Users $model
 * @var boolean $boss
 */

use app\models\users\Users;
use yii\web\View;
use yii\helpers\Html;

?>
<div class="fixed-fluid pull-left">
	<div class="pull-sm-left">
		<div class="panel">
			<div class="text-center pad-all bord-btm">
				<div class="pad-ver">
					<img src="<?= $model->avatar; ?>" class="img-lg img-border img-circle" alt="<?= $model->username; ?>">
				</div>

				<h4 class="text-lg mar-no" style="white-space: nowrap;"><?= Html::a($model->username, ['admin/users/update', 'id' => $model->id]) ?></h4>
			</div>
			<div class="mar-btm">
				<p class="text-semibold text-main pad-all mar-no"><?= $model->positionName; ?></p>
			</div>
		</div>
	</div>
</div>