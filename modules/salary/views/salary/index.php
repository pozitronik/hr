<?php /** @noinspection MissedFieldInspection */
declare(strict_types = 1);

/**
 * @var View $this
 * @var ActiveRecord $searchModel
 * @var ActiveDataProvider $dataProvider
 */

use app\helpers\Icons;
use app\modules\salary\models\SalaryFork;
use app\modules\salary\widgets\navigation_menu\SalaryForkMenuWidget;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\web\View;
use kartik\grid\GridView;
use yii\bootstrap\Html;

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
			'value' => function(SalaryFork $model) {
				return SalaryForkMenuWidget::widget([
					'model' => $model,
					'mode' => SalaryForkMenuWidget::MODE_ACTION_COLUMN_MENU
				]);
			},
			'format' => 'raw'
		],
		[
			'attribute' => 'refUserPosition.name'
		],
		[
			'attribute' => 'refGrade.name'
		],
		[
			'attribute' => 'refPremiumGroup.name'
		],
		[
			'attribute' => 'refLocation.name'
		],
		[
			'attribute' => 'min'
		],
		[
			'attribute' => 'max'
		],

	]
]); ?>