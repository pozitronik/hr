<?php
declare(strict_types = 1);

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
use app\modules\references\models\Reference;
$this->title = "Просмотр записи в справочнике ".$model->menuCaption;
$this->params['breadcrumbs'][] = ['label' => 'Управление', 'url' => ['/admin']];
$this->params['breadcrumbs'][] = ['label' => 'Справочники', 'url' => ['/references/references']];
$this->params['breadcrumbs'][] = ['label' => $model->menuCaption, 'url' => ['/references/references/index', 'class' => $model->formName()]];
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
								['update', 'id' => $model->id, 'class' => $model->formName()],
								['class' => 'btn btn-primary']
						); ?>
						<?= Html::a(
								'Удалить',
								['delete', 'id' => $model->id, 'class' => $model->formName()],
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