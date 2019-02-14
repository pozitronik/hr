<?php
declare(strict_types = 1);

use app\helpers\Icons;
use app\modules\references\widgets\navigation_menu\ReferenceNavigationMenuWidget;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\data\ActiveDataProvider;
use yii\web\View;
use app\modules\references\models\Reference;

/**
 * @var View $this ;
 * @var ActiveDataProvider $dataProvider
 * @var Reference|false $class
 * @var stdClass $searchModel
 */

$this->title = $class->menuCaption;
$this->params['breadcrumbs'][] = ['label' => 'Управление', 'url' => ['/admin']];
$this->params['breadcrumbs'][] = ['label' => 'Справочники', 'url' => ['/references/references']];
$this->params['breadcrumbs'][] = $this->title;

$columns[] = [
	'filter' => false,
	'header' => Icons::menu(),
	'mergeHeader' => true,
	'headerOptions' => [
		'class' => 'skip-export kv-align-center kv-align-middle'
	],
	'contentOptions' => [
		'style' => 'width:50px',
		'class' => 'skip-export kv-align-center kv-align-middle'
	],
	'value' => function(Reference $model) use ($class) {
		return ReferenceNavigationMenuWidget::widget([
			'model' => $model,
			'className' => $class->formName(),
			'mode' => ReferenceNavigationMenuWidget::MODE_ACTION_COLUMN_MENU
		]);
	},
	'format' => 'raw'
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