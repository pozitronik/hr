<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var GroupsSearch $searchModel
 * @var ActiveDataProvider $dataProvider
 */

use app\assets\IsotopeAsset;
use app\assets\MasonryAsset;
use app\modules\groups\models\GroupsSearch;
use app\modules\groups\models\references\RefGroupTypes;
use app\widgets\button_controls\ButtonControlsWidget;
use app\widgets\group_card\GroupCardWidget;
use pozitronik\helpers\ArrayHelper;
use yii\bootstrap\Html;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\View;

$this->title = 'Мои группы';
$this->params['breadcrumbs'][] = $this->title;
$dataProvider->pagination = false;
MasonryAsset::register($this);
IsotopeAsset::register($this);
$this->registerJs("normalize_widths()", View::POS_END);
$this->registerJs("var Msnry = new Masonry('.grid',{columnWidth: '.grid-sizer', itemSelector: '.panel-card', percentPosition: true, fitWidth: true}); ", View::POS_END);
$this->registerJs("var Controls = new DashboardControl('.grid', '.panel-card', function() {Msnry.layout()})", View::POS_END);
/*Временный код: генерируем список типов групп у пользюка в скопе*/

$userGroupTypes = RefGroupTypes::getGroupsTypesScope(ArrayHelper::getColumn($dataProvider->models, 'type'));
array_walk($userGroupTypes, function(&$value, &$key) {
	$key = "filter-type{$value['id']}";
	$value = [
		'label' => $value['name'],
		'value' => $key,
		'options' => [
			'data-filter' => $value['id'],
			'checked' => 'checked'
		]
	];
});
?>

<div class="panel">
	<div class="panel-heading">
		<table class="panel-header-table">
			<tr>
				<td style="width: 1%">
					<h3 class="panel-title pull-left">
						<?= Html::encode($this->title) ?>
					</h3>
				</td>
				<td>
					<div class="panel-center">
						<?= ButtonControlsWidget::widget([
							'name' => 'sorting',
							'items' => [
								'sort-by-type' => [
									'label' => 'По типу',
									'options' => [
										'data-sorting' => 'type'
									]
								],
								'sort-by-count' => [
									'label' => 'По сотрудникам',
									'options' => [
										'data-sorting' => 'count'
									]
								],
								'sort-by-vacancy' => [
									'label' => 'По вакансиям',
									'options' => [
										'data-sorting' => 'vacancy'
									]
								]
							],
							'radioMode' => true
						]) ?>
						<?= ButtonControlsWidget::widget([
							'name' => 'filter',
							'items' => $userGroupTypes
						]) ?>
					</div>
				</td>
				<td>

					<div class="panel-control">
						<?= Html::a("Таблица", Url::current(['t' => 1]), [
							'class' => 'btn btn-xs btn-info'
						]) ?>
					</div>
				</td>
			</tr>
		</table>


	</div>
</div>
<div class="grid">
	<div class="grid-sizer"></div>
	<?php foreach ($dataProvider->models as $group): ?>
		<?= GroupCardWidget::widget(['group' => $group]) ?>
	<?php endforeach; ?>
</div>


