<?php
declare(strict_types = 1);

use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\data\ActiveDataProvider;
use yii\web\View;
use app\models\references\Reference;

/**
 * @var View $this ;
 * @var ActiveDataProvider $dataProvider
 * @var Reference|false $class
 * @var stdClass $searchModel
 */

$this->title = $class->menuCaption;
$this->params['breadcrumbs'][] = ['label' => 'Управление', 'url' => ['/admin']];
$this->params['breadcrumbs'][] = ['label' => 'Справочники', 'url' => ['/admin/references']];
$this->params['breadcrumbs'][] = $this->title;

$columns = $class->columns;
$columns[] = [
	'class' => ActionColumn::class,
	'buttons' => [
		'view' => function($url, $model) use ($class) {
			/** @var Reference $model */
			return Html::a(
				'<span class="glyphicon glyphicon-eye-open"></span>',
				['view', 'class' => $class->formName(), 'id' => $model->id]
			);
		},
		'update' => function($url, $model) use ($class) {
			/** @var Reference $model */
			return Html::a(
				'<span class="glyphicon glyphicon-pencil"></span>',
				['update', 'class' => $class->formName(), 'id' => $model->id]
			);
		},
		'delete' => function($url, $model) use ($class) {
			/** @var Reference $model */
			return Html::a(
				'<span class="glyphicon glyphicon-trash"></span>',
				['delete', 'class' => $class->formName(), 'id' => $model->id],
				[
					'title' => 'Удалить запись',
					'data' => [
						'confirm' => $model->deleted?'Вы действительно хотите восстановить запись?':'Вы действительно хотите удалить запись?',
						'method' => 'post'
					]
				]
			);
		}
	]
];
?>
<div class="row">
	<div class="col-xs-12">
		<div class="panel">
			<div class="panel-heading">
				<div class="panel-control">
					<?= Html::a('Создать запись', ['create', 'class' => $class->formName()], ['class' => 'btn btn-success']); ?>
				</div>
				<h3 class="panel-title"><?= Html::encode($this->title); ?></h3>
			</div>
			<div class="panel-body">
				<?= GridView::widget([
					'filterModel' => $searchModel,
					'dataProvider' => $dataProvider,
					'columns' => $columns,
					'rowOptions' => function($record) {
						$class = '';
						if ($record['deleted']) {
							$class .= 'danger ';
						}
						return ['class' => $class];
					}
				]); ?>
			</div>
		</div>
	</div>
</div>