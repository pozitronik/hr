<?php /** @noinspection MissedFieldInspection */
declare(strict_types = 1);

/**
 * @var View $this
 * @var ActiveRecord $searchModel
 * @var ActiveDataProvider $dataProvider
 */

use app\helpers\Icons;
use app\modules\privileges\models\Privileges;
use app\modules\privileges\widgets\navigation_menu\PrivilegesNavigationMenuWidget;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\ActiveRecord;
use yii\web\View;
use kartik\grid\GridView;
use yii\bootstrap\Html;

$this->title = 'Привилегии';
$this->params['breadcrumbs'][] = $this->title;
?>

<?= GridView::widget([
	'dataProvider' => $dataProvider,
	'panel' => [
		'heading' => $this->title
	],
	'toolbar' => [
		[
			'content' => Html::a('Новая привилегия', ['create'], ['class' => 'btn btn-success'])
		],
		[
			'content' => Html::a('Новое правило', ['dynamic-rights/create'], ['class' => 'btn btn-success'])
		]
	],
	'export' => false,
	'resizableColumns' => true,
	'responsive' => true,
	'columns' => [
		[
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
			'value' => static function(Privileges $model) {
				return PrivilegesNavigationMenuWidget::widget([
					'model' => $model,
					'mode' => PrivilegesNavigationMenuWidget::MODE_ACTION_COLUMN_MENU
				]);
			},
			'format' => 'raw'
		],
		[
			'attribute' => 'name',
			'value' => static function(Privileges $model) {
				return Html::a($model->name, ['update', 'id' => $model->id]);
			},
			'format' => 'raw'
		],
		'default:boolean',
		[
			'attribute' => 'userRights',
			'value' => static function(Privileges $model) {
				return GridView::widget([
					'dataProvider' => new ArrayDataProvider([
						'allModels' => $model->userRights
					]),
					'panel' => false,
					'summary' => false,
					'headerRowOptions' => [
						'style' => 'display:none'
					],
					'toolbar' => false,
					'export' => false,
					'resizableColumns' => false,
					'responsive' => true,
					'options' => [
						'class' => 'grid_view_cell'
					],
					'columns' => [
						[
							'attribute' => 'name',
							'options' => [
								'style' => 'width:20%'
							]
						],
						[
							'attribute' => 'description',
							'format' => 'raw'
						]
					]
				]);
			},
			'format' => 'raw'
		],
		[
			'attribute' => 'usersCount',
			'value' => static function(Privileges $model) {
				return $model->default?"Все пользователи":$model->usersCount;
			}
		]
	]
]) ?>