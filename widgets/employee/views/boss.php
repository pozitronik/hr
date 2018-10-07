<?php
declare(strict_types = 1);

/* @var View $this
 * @var Users $model
 */

use app\models\users\Users;
use yii\web\View;

?>
<div class="fixed-fluid">
	<div class="fixed-sm-250 fixed-lg-250" style="margin-left: auto; margin-right: auto">
		<div class="panel">
			<div class="text-center pad-all bord-btm">
				<div class="pad-ver">
					<div class="crown"></div>
					<img src="<?= $model->avatar; ?>" class="img-lg img-border img-circle" alt="<?= $model->username; ?>">
				</div>
				<h4 class="text-lg mar-no"><?= $model->username; ?></h4>
			</div>
			<div class="mar-btm">
				<p class="text-semibold text-main pad-all mar-no text-lg-center"><?= $model->comment ?></p>
			</div>
		</div>
	</div>
</div>