<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var ActiveDataProvider $dataProvider
 * @var TargetsSearch $searchModel
 */

use app\helpers\IconsHelper;
use app\modules\dynamic_attributes\widgets\navigation_menu\AttributeNavigationMenuWidget;
use app\modules\references\widgets\reference_select\ReferenceSelectWidget;
use app\modules\targets\models\references\RefTargetsTypes;
use app\modules\targets\models\Targets;
use app\modules\targets\models\TargetsSearch;
use app\modules\targets\TargetsModule;
use app\modules\targets\widgets\navigation_menu\TargetNavigationMenuWidget;
use app\widgets\badge\BadgeWidget;
use kartik\grid\DataColumn;
use kartik\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;

$this->title = 'Целеполагание';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="panel">
	<div class="panel-heading">
		<div class="panel-control">
			<?= Html::a('Создать запись', ['create'], ['class' => 'btn btn-success']) ?>
		</div>
		<h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
	</div>
	<div class="panel-body">
		<?= GridView::widget([
			'filterModel' => $searchModel,
			'dataProvider' => $dataProvider,
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
					'value' => static function(Targets $model) {
						return TargetNavigationMenuWidget::widget([
							'model' => $model,
							'mode' => AttributeNavigationMenuWidget::MODE_ACTION_COLUMN_MENU
						]);
					},
					'format' => 'raw'
				],
				[
					'attribute' => 'name',
					'value' => static function(Targets $model) {
						return BadgeWidget::widget([
							'models' => $model,
							'attribute' => 'name',
							"badgeOptions" => [
								'class' => "badge badge-info"
							],
							"optionsMap" => RefTargetsTypes::colorStyleOptions(),
							"optionsMapAttribute" => 'type',
							'linkScheme' => [TargetsModule::to('targets/profile'), 'id' => $model->id]
						]);
					},
					'format' => 'raw'
				],
				[
					'class' => DataColumn::class,
					'attribute' => 'type',
					'value' => static function(Targets $target) {
						return BadgeWidget::widget([
							'models' => $target->relTargetsTypes,
							'useBadges' => true,
							'attribute' => 'name',
							'unbadgedCount' => 3,
							'itemsSeparator' => false,
							"optionsMap" => RefTargetsTypes::colorStyleOptions(),
							'linkScheme' => [TargetsModule::to(), 'TargetsSearch[type]' => 'id']
						]);
					},
					'format' => 'raw',
					'filterType' => ReferenceSelectWidget::class,
					'filterInputOptions' => ['placeholder' => 'Тип'],
					'filterWidgetOptions' => [
						/*В картиковском гриде захардкожено взаимодействие с собственными фильтрами, в частности использование filter. В нашем виджете обходимся так*/
						'referenceClass' => RefTargetsTypes::class,
						'pluginOptions' => ['allowClear' => true]
					]
				],
			],

			'rowOptions' => static function($record) {
				$class = '';
				if ($record['deleted']) {
					$class .= 'danger ';
				}
				return ['class' => $class];
			}
		]) ?>
	</div>
</div>
