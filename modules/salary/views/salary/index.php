<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var ActiveRecord $searchModel
 * @var ActiveDataProvider $dataProvider
 * @var SalaryForkSearch $searchModel
 */

use app\models\core\IconsHelper;
use app\components\pozitronik\references\widgets\reference_select\ReferenceSelectWidget;
use app\modules\salary\models\references\RefGrades;
use app\modules\salary\models\references\RefLocations;
use app\modules\salary\models\references\RefSalaryPremiumGroups;
use app\modules\salary\models\references\RefUserPositions;
use app\modules\salary\models\SalaryFork;
use app\modules\salary\models\SalaryForkSearch;
use app\modules\salary\widgets\navigation_menu\SalaryForkMenuWidget;
use kartik\grid\DataColumn;
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
	'filterModel' => $searchModel,
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
			'class' => DataColumn::class,
			'filter' => false,
			'header' => IconsHelper::menu(),
			'mergeHeader' => true,
			'headerOptions' => [
				'class' => 'skip-export kv-align-center kv-align-middle'
			],
			'contentOptions' => [
				'style' => 'width:50px',
				'class' => 'skip-export kv-align-center kv-align-middle'
			],
			'value' => static function(SalaryFork $model) {
				return SalaryForkMenuWidget::widget([
					'model' => $model,
					'mode' => SalaryForkMenuWidget::MODE_ACTION_COLUMN_MENU
				]);
			},
			'format' => 'raw'
		],
		[
			'class' => DataColumn::class,
			'attribute' => 'positionId',
			'format' => 'raw',
			'value' => 'refUserPosition.name',
			'label' => 'Должность',
			'filter' => $searchModel->positionId,
			'filterType' => ReferenceSelectWidget::class,
			'filterInputOptions' => ['placeholder' => 'Выберите должность'],
			'filterWidgetOptions' => [
				'referenceClass' => RefUserPositions::class,
				'pluginOptions' => ['allowClear' => true, 'multiple' => true]
			]
		],
		[
			'class' => DataColumn::class,
			'attribute' => 'gradeId',
			'value' => 'refGrade.name',
			'label' => 'Грейд',
			'format' => 'raw',
			'filter' => $searchModel->gradeId,
			'filterType' => ReferenceSelectWidget::class,
			'filterInputOptions' => ['placeholder' => 'Выберите грейд'],
			'filterWidgetOptions' => [
				'referenceClass' => RefGrades::class,
				'pluginOptions' => ['allowClear' => true, 'multiple' => true]
			]
		],
		[
			'class' => DataColumn::class,
			'attribute' => 'premiumGroupId',
			'value' => 'refPremiumGroup.name',
			'label' => 'Группа премирования',
			'format' => 'raw',
			'filter' => $searchModel->premiumGroupId,
			'filterType' => ReferenceSelectWidget::class,
			'filterInputOptions' => ['placeholder' => 'Выберите группу премирования'],
			'filterWidgetOptions' => [
				'referenceClass' => RefSalaryPremiumGroups::class,
				'pluginOptions' => ['allowClear' => true, 'multiple' => true]
			]
		],
		[
			'class' => DataColumn::class,
			'attribute' => 'locationId',
			'value' => 'refLocation.name',
			'label' => 'Расположение',
			'format' => 'raw',
			'filter' => $searchModel->locationId,
			'filterType' => ReferenceSelectWidget::class,
			'filterInputOptions' => ['placeholder' => 'Выберите локацию'],
			'filterWidgetOptions' => [
				'referenceClass' => RefLocations::class,
				'pluginOptions' => ['allowClear' => true, 'multiple' => true]
			]
		],
		[
			'attribute' => 'min'
		],
		[
			'attribute' => 'max'
		],
		[
			'attribute' => 'mid'
		]

	]
]) ?>