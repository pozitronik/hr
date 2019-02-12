<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Groups $model
 */

use app\modules\groups\models\Groups;
use app\modules\groups\widgets\navigation_menu\NavigationMenuWidget;
use kartik\helpers\Html;
use yii\web\View;

$this->title = "Пользователи в группе";
$this->params['breadcrumbs'][] = ['label' => 'Управление', 'url' => ['/admin']];
$this->params['breadcrumbs'][] = ['label' => 'Группы', 'url' => ['/groups/groups']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['/groups/groups/profile', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="panel panel-default">
	<div class="panel-heading">
		<div class="panel-control">
			<?= NavigationMenuWidget::widget([
				'model' => $model
			]); ?>
		</div>
		<h3 class="panel-title"><?= Html::encode($this->title); ?></h3>
	</div>

	<div class="panel-body">
		<div class="row">
			<div class="col-md-126">
				<?= $this->render('users/index', [
					'model' => $model,
					'heading' => '<label class="control-label">Пользователи в группе</label>',
					'selectorInPanel' => true,
					'showRolesSelector' => true,
					'showDropColumn' => true
				]); ?>
			</div>
		</div>
	</div>
</div>