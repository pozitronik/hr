<?php
declare(strict_types = 1);

use app\helpers\IconsHelper;
use app\modules\dynamic_attributes\widgets\navigation_menu\AttributeNavigationMenuWidget;
use app\modules\targets\assets\TargetsAsset;
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
use yii\i18n\Formatter;
use yii\web\View;

/**
 * @var View $this
 * @var ActiveDataProvider $dataProvider
 * @var TargetsSearch $searchModel
 */

$this->title = 'Мои цели';
$this->params['breadcrumbs'][] = TargetsModule::breadcrumbItem('Целеполагание');
$this->params['breadcrumbs'][] = $this->title;
TargetsAsset::register($this);
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
			'formatter' => [
				'class' => Formatter::class,
				'nullDisplay' => '',
			],
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
					'class' => DataColumn::class,
					'attribute' => 'name',
					'label' => 'Веха',
					'value' => static function(Targets $model) {
						return BadgeWidget::widget([
							'models' => $model,
							'attribute' => 'name',
							"badgeOptions" => [
								'class' => "badge badge-target"
							],
							'itemsSeparator' => false,
							"optionsMap" => RefTargetsTypes::colorStyleOptions(),
							"optionsMapAttribute" => 'type',
							'linkScheme' => [TargetsModule::to('targets/update'), 'id' => 'id']
						]);
					},
					'format' => 'raw',
					'group' => true
				],
				[
					'class' => DataColumn::class,
					'attribute' => 'is_year',
					'value' => static function(Targets $model) {
						return BadgeWidget::widget([
							'models' => $model->getQuarterTargets()->all(),
							'attribute' => 'name',
							"badgeOptions" => [
								'class' => "badge badge-target"
							],
							'itemsSeparator' => false,
							"optionsMap" => RefTargetsTypes::colorStyleOptions(),
							"optionsMapAttribute" => 'type',
							'linkScheme' => [TargetsModule::to('targets/update'), 'id' => $model->id]
						]);
					},
					'format' => 'raw'
				],
				[
					'class' => DataColumn::class,
					'attribute' => 'q1',
					'value' => static function(Targets $model) {
						return BadgeWidget::widget([
							'models' => $model->getQuarterTargets(1)->all(),
							'attribute' => 'name',
							"badgeOptions" => [
								'class' => "badge badge-target"
							],
							'itemsSeparator' => false,
							"optionsMap" => RefTargetsTypes::colorStyleOptions(),
							"optionsMapAttribute" => 'type',
							'linkScheme' => [TargetsModule::to('targets/update'), 'id' => $model->id]
						]);
					},
					'format' => 'raw'
				],
				[
					'class' => DataColumn::class,
					'attribute' => 'q2',
					'value' => static function(Targets $model) {
						return BadgeWidget::widget([
							'models' => $model->getQuarterTargets(2)->all(),
							'attribute' => 'name',
							"badgeOptions" => [
								'class' => "badge badge-target"
							],
							'itemsSeparator' => false,
							"optionsMap" => RefTargetsTypes::colorStyleOptions(),
							"optionsMapAttribute" => 'type',
							'linkScheme' => [TargetsModule::to('targets/update'), 'id' => $model->id]
						]);
					},
					'format' => 'raw'
				],
				[
					'class' => DataColumn::class,
					'attribute' => 'q3',
					'value' => static function(Targets $model) {
						return BadgeWidget::widget([
							'models' => $model->getQuarterTargets(3)->all(),
							'attribute' => 'name',
							"badgeOptions" => [
								'class' => "badge badge-target"
							],
							'itemsSeparator' => false,
							"optionsMap" => RefTargetsTypes::colorStyleOptions(),
							"optionsMapAttribute" => 'type',
							'linkScheme' => [TargetsModule::to('targets/update'), 'id' => $model->id]
						]);
					},
					'format' => 'raw'
				],
				[
					'class' => DataColumn::class,
					'attribute' => 'q4',
					'value' => static function(Targets $model) {
						return BadgeWidget::widget([
							'models' => $model->getQuarterTargets(4)->all(),
							'attribute' => 'name',
							"badgeOptions" => [
								'class' => "badge badge-target"
							],
							'itemsSeparator' => false,
							"optionsMap" => RefTargetsTypes::colorStyleOptions(),
							"optionsMapAttribute" => 'type',
							'linkScheme' => [TargetsModule::to('targets/update'), 'id' => $model->id]
						]);
					},
					'format' => 'raw'
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
