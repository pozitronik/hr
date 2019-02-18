<?php
declare(strict_types = 1);

/* @var View $this */

use app\modules\groups\models\Groups;
use app\modules\users\widgets\user_select\UserSelectWidget;
use yii\web\View;

$model = Groups::findModel(12);
$model2 = Groups::findModel(19);
?>

<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title">Заголовок</h3>
	</div>

	<div class="panel-body">
		<div class="row">
			<div class="col-md-6">
				<?= UserSelectWidget::widget([
					'model' => $model,
					'attribute' => 'relUsers',
					'notData' => $model->relUsers,
					'multiple' => true,
					'mode' => UserSelectWidget::MODE_AJAX,
					'formAction' => ['/groups/groups/profile', 'id' => $model->primaryKey]
				]) ?>
			</div>
			<div class="col-md-6">
			</div>
		</div>

	</div>
</div>