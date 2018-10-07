<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Users $user
 * @var Workgroups $workgroup
 */

use app\models\users\Users;
use app\models\workgroups\Workgroups;
use yii\web\View;
use app\widgets\employee\EmployeeWidget;
?>

<div class="panel">
	<div class="panel-heading">
		<h3 class="panel-title"><?= $workgroup->name; ?></h3>
	</div>
	<div class="panel-body">
		<?php foreach ($workgroup->employees as $employee): ?>
			<?= EmployeeWidget::widget([
				'user' => $employee
			]) ?>
		<?php endforeach; ?>
	</div>
</div>

