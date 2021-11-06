<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var ActiveDataProvider $dataProvider
 * @var TargetsSearch $searchModel
 */

use app\models\core\IconsHelper;
use app\modules\dynamic_attributes\widgets\navigation_menu\AttributeNavigationMenuWidget;
use app\modules\groups\models\references\RefGroupTypes;
use app\components\pozitronik\references\widgets\reference_select\ReferenceSelectWidget;
use app\modules\salary\models\references\RefUserPositions;
use app\modules\targets\TargetsAsset;
use app\modules\targets\models\references\RefTargetsResults;
use app\modules\targets\models\references\RefTargetsTypes;
use app\modules\targets\models\Targets;
use app\modules\targets\models\TargetsSearch;
use app\modules\targets\TargetsModule;
use app\modules\targets\widgets\navigation_menu\TargetNavigationMenuWidget;
use app\modules\users\UsersModule;
use app\components\pozitronik\widgets\BadgeWidget;
use kartik\grid\DataColumn;
use kartik\grid\GridView;
use app\components\pozitronik\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;

$this->title = 'Целеполагание';
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
					'attribute' => 'parent_name',
					'label' => 'Родительское задание',
					'value' => static function(Targets $model) {
						return BadgeWidget::widget([
							'models' => $model->relParentTarget,
							'attribute' => 'name',
							"badgeOptions" => [
								'class' => "badge badge-target"
							],
							"optionsMap" => RefTargetsTypes::colorStyleOptions(),
							"optionsMapAttribute" => 'type',
							'linkScheme' => [TargetsModule::to('targets/update'), 'id' => 'id']
						]);
					},
					'format' => 'raw',
//					'group' => true
				],
				[
					'class' => DataColumn::class,
					'attribute' => 'name',
					'value' => static function(Targets $model) {
						return BadgeWidget::widget([
							'models' => $model,
							'attribute' => 'name',
							"badgeOptions" => [
								'class' => "badge badge-target"
							],
							"optionsMap" => RefTargetsTypes::colorStyleOptions(),
							"optionsMapAttribute" => 'type',
							'linkScheme' => [TargetsModule::to('targets/update'), 'id' => $model->id]
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
				[
					'class' => DataColumn::class,
					'attribute' => 'result_type',
					'value' => static function(Targets $target) {
						return BadgeWidget::widget([
							'models' => $target->relTargetsResults,
							'useBadges' => true,
							'attribute' => 'name',
							'unbadgedCount' => 3,
							'itemsSeparator' => false,
							"optionsMap" => RefTargetsResults::colorStyleOptions(),
							'linkScheme' => [TargetsModule::to(), 'TargetsSearch[result_type]' => 'id']
						]);
					},
					'format' => 'raw',
					'filterType' => ReferenceSelectWidget::class,
					'filterInputOptions' => ['placeholder' => 'Результат'],
					'filterWidgetOptions' => [
						/*В картиковском гриде захардкожено взаимодействие с собственными фильтрами, в частности использование filter. В нашем виджете обходимся так*/
						'referenceClass' => RefTargetsResults::class,
						'pluginOptions' => ['allowClear' => true]
					]
				],
				[
					'class' => DataColumn::class,
					'attribute' => 'quarter',
					'label' => 'Период',
					'value' => static function(Targets $target) {
						return $target->isFinal?BadgeWidget::widget([
							'models' => ArrayHelper::getValue($target, 'relTargetsPeriods.asFilePeriod'),//todo fixme: период у цели может не существовать, если цель преобразована из задания другого типа
							'useBadges' => true,
							'itemsSeparator' => false
						]):'Не применимо';
					},
					'format' => 'raw'
				],
				[
					'class' => DataColumn::class,
					'attribute' => 'group_name',
					'label' => 'Ответственная группа',
					'value' => static function(Targets $target) {
						return BadgeWidget::widget([
							'models' => $target->relGroups,
							'useBadges' => true,
							'attribute' => 'name',
							'itemsSeparator' => false,
							"optionsMap" => RefGroupTypes::colorStyleOptions(),
							'linkScheme' => [TargetsModule::to('targets/group'), 'id' => 'id']
						]);
					},
					'format' => 'raw'
				],
				[
					'class' => DataColumn::class,
					'attribute' => 'user_name',
					'label' => 'Ответственный сотрудник',
					'value' => static function(Targets $target) {
						return BadgeWidget::widget([
							'models' => $target->relUsers,
							'useBadges' => true,
							'attribute' => 'username',
							'itemsSeparator' => false,
							"optionsMap" => RefUserPositions::colorStyleOptions(),
							'linkScheme' => [UsersModule::to('users/profile'), 'id' => 'id']
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
