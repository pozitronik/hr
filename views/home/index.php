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

$this->title = 'Панель управления';
?>
<?= EmployeeWidget::widget([
	'user' => Users::findModel(1),
	'mode' => 'boss'
]) ?>
<div class="panel">
	<div class="panel-heading">
		<h3 class="panel-title">Команда "Пятничные алкаши"</h3>
	</div>
	<div class="panel-body">
		<?= EmployeeWidget::widget([
			'user' => Users::findModel(1)
		]) ?>
	</div>
</div>
