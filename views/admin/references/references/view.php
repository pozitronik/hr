<?php
/**
 * @var View $this
 * @var ActiveDataProvider $dataProvider
 * @var Reference|false $class
 * @var Reference $model
 */

use yii\data\ActiveDataProvider;
use yii\web\View;
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\references\Reference;

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Админка', 'url' => ['admin/admin']];
$this->params['breadcrumbs'][] = ['label' => 'Справочники', 'url' => ['admin/references']];
$this->params['breadcrumbs'][] = ['label' => $model->ref_name, 'url' => ['index', 'class' => $model->classNameShort]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
	<div class="col-xs-12">
		<div class="panel">
			<div class="panel-heading">
				<div class="panel-control">
					<div class="btn-group">
						<?= Html::a(
								'Изменить',
								['update', 'id' => $model->id, 'class' => $model->classNameShort],
								['class' => 'btn btn-primary']
						); ?>
						<?= Html::a(
								'Удалить',
								['delete', 'id' => $model->id, 'class' => $model->classNameShort],
								[
									'class' => 'btn btn-danger',
									'data' => [
										'confirm' => 'Вы действительно хотите удалить эту запись?',
										'method' => 'post'
									]
								]
						); ?>
					</div>
				</div>
				<h3 class="panel-title"><?= Html::encode($this->title); ?></h3>
			</div>
			<div class="panel-body">
				<?= DetailView::widget([
					'model' => $model,
					'attributes' => $model->view_columns
				]); ?>
			</div>
		</div>
	</div>
</div>