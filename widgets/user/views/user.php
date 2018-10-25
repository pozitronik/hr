<?php
declare(strict_types = 1);

/* @var View $this
 * @var Users $model
 * @var boolean $boss
 */

use app\models\users\Users;
use yii\web\View;

?>
<div class="fixed-fluid pull-left">
	<div class="pull-sm-left">
		<div class="panel">
			<div class="text-center pad-all bord-btm">
				<div class="pad-ver">
					<?php if ($boss): ?>
						<div class="crown"></div>
					<?php endif; ?>
					<img src="<?= $model->avatar; ?>" class="img-lg img-border img-circle" alt="<?= $model->username; ?>">
				</div>
				<h4 class="text-lg mar-no" style="white-space: nowrap;"><?= $model->username; ?></h4>
			</div>
			<div class="mar-btm">
				<p class="text-semibold text-main pad-all mar-no"><?= $model->positionName; ?></p>
			</div>
		</div>
	</div>
</div>