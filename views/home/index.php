<?php
declare(strict_types = 1);

/**
 * Главная страница пользователя при входе
 * @var View $this
 * @var Users $model
 */

use app\models\users\Users;
use yii\web\View;
use app\widgets\employee\EmployeeWidget;
use app\widgets\workgroup\WorkgroupWidget;

$this->title = 'Панель управления';
?>
<?= EmployeeWidget::widget([
	'user' => $model,
	'mode' => 'boss'
]) ?>

<?php foreach ($model->workgroups as $workgroup): ?>
	<?= WorkgroupWidget::widget([
		'workgroup' => $workgroup,
		'user' => $model
	]) ?>
<?php endforeach;?>
