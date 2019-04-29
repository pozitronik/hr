<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Groups $model
 * @var ActiveDataProvider $parentProvider
 * @var ActiveDataProvider $childProvider
 */

use app\modules\groups\GroupsModule;
use app\modules\groups\models\Groups;
use app\modules\groups\widgets\navigation_menu\GroupNavigationMenuWidget;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;

$this->title = "Иерархия группы {$model->name}";
$this->params['breadcrumbs'][] = GroupsModule::breadcrumbItem('Группы');
$this->params['breadcrumbs'][] = GroupsModule::breadcrumbItem($model->name, ['groups/profile', 'id' => $model->id]);
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="panel panel-default">
	<div class="panel-heading">
		<div class="panel-control">
			<?= GroupNavigationMenuWidget::widget([
				'model' => $model
			]) ?>
		</div>
		<h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
	</div>

	<div class="panel-body">
		<div class="row">
			<div class="col-md-6">
				<?= $this->render('parent', [
					'model' => $model,
					'provider' => $parentProvider,
					'heading' => '<label class="control-label">Родительские группы</label>'
				]) ?>
			</div>
			<div class="col-md-6">
				<?= $this->render('child', [
					'model' => $model,
					'provider' => $childProvider,
					'heading' => '<label class="control-label">Дочерние группы</label>'
				]) ?>
			</div>
		</div>

	</div>
</div>