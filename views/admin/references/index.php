<?php
declare(strict_types = 1);

use app\helpers\Icons;
use kartik\grid\ActionColumn;
use kartik\grid\GridView;
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

$columns[] = [
	'header' => Icons::menu(),
	'dropdown' => true,
	'dropdownButton' => [
		'label' => Icons::menu(),
		'caret' => ''
	],
	'class' => ActionColumn::class,
	'buttons' => [
		'view' => function($url, $model) use ($class) {
			/** @var Reference $model */
			return Html::tag('li', Html::a(Icons::view().'Просмотр', ['view', 'class' => $class->formName(), 'id' => $model->id]));

		},
		'update' => function($url, $model) use ($class) {
			/** @var Reference $model */
			return Html::tag('li', Html::a(Icons::update().'Изменение', ['update', 'class' => $class->formName(), 'id' => $model->id]));
		},
		'delete' => function($url, $model) use ($class) {
			/** @var Reference $model */
			return Html::tag('li', Html::a(Icons::delete().'Удаление', ['delete', 'class' => $class->formName(), 'id' => $model->id], [
				'title' => 'Удалить запись',
				'data' => [
					'confirm' => $model->deleted?'Вы действительно хотите восстановить запись?':'Вы действительно хотите удалить запись?',
					'method' => 'post'
				]
			]));
		}
	]
];

$columns = array_merge($columns, $class->columns);

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