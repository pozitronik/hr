<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var ActiveRecord $searchModel
 * @var ActiveDataProvider $dataProvider
 */

use app\helpers\Icons;
use app\modules\privileges\models\Privileges;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\ActiveRecord;
use yii\web\View;
use kartik\grid\GridView;
use yii\bootstrap\Html;
use kartik\grid\ActionColumn;

$this->title = 'Зарплатные вилки';
$this->params['breadcrumbs'][] = $this->title;
?>
<?= GridView::widget([
	'dataProvider' => $dataProvider,
	'panel' => [
		'heading' => $this->title
	],
	'toolbar' => [
		[
			'content' => Html::a('Новый', ['create'], ['class' => 'btn btn-success'])
		]
	],
	'export' => false,
	'resizableColumns' => true,
	'responsive' => true,
	'columns' => [
		[
			'class' => ActionColumn::class,
			'header' => Icons::menu(),
			'dropdown' => true,
			'dropdownButton' => [
				'label' => Icons::menu(),
				'caret' => ''
			],
			'template' => '{update} {delete}',
			'buttons' => [
				'update' => function($url, $model) {
					/** @var Privileges $model */
					return Html::tag('li', Html::a(Icons::update().'Изменение', ['update', 'id' => $model->id]));
				},
				'delete' => function($url, $model) {
					/** @var Privileges $model */
					return Html::tag('li', Html::a(Icons::delete().'Удаление', ['delete', 'id' => $model->id], [
						'title' => 'Удалить запись',
						'data' => [
							'confirm' => $model->deleted?'Вы действительно хотите восстановить запись?':'Вы действительно хотите удалить запись?',
							'method' => 'post'
						]
					]));
				}
			]
		],
		[
			'attribute' => 'name',
			'value' => function($model) {
				/** @var Privileges $model */
				return Html::a($model->name, ['update', 'id' => $model->id]);
			},
			'format' => 'raw'
		],
		'default:boolean',
		[
			'attribute' => 'userRights',
			'value' => function($model) {
				/** @var Privileges $model */
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
						'description'
					]
				]);
			},
			'format' => 'raw'
		],
		[
			'attribute' => 'usersCount',
			'value' => function($model) {
				/** @var Privileges $model */
				return $model->default?"Все пользователи":$model->usersCount;
			}
		]
	]
]); ?>