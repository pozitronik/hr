<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Groups $model
 */

use app\modules\groups\models\Groups;
use app\modules\groups\widgets\navigation_menu\NavigationMenuWidget;
use yii\helpers\Html;
use yii\web\View;

$this->title = "Иерархия группы {$model->name}";
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
			<div class="col-md-6">

				<?= $this->render('parent_groups/index', [
					'model' => $model,
					'heading' => '<label class="control-label">Родительские группы</label>'
				]); ?>
			</div>
			<div class="col-md-6">
				<?= $this->render('child_groups/index', [
					'model' => $model,
					'heading' => '<label class="control-label">Дочерние группы</label>'
				]); ?>
			</div>
		</div>

	</div>
</div>