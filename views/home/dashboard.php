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
use app\widgets\button_controls\ButtonControlsWidget;
use app\widgets\group_card\GroupCardWidget;
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
$this->registerJs("init_isotope()", View::POS_END);
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
								'sort-by-type' => 'По типу',
								'sort-by-count' => 'По сотрудникам',
								'sort-by-vacancy' => 'По вакансиям'
							],
							'radioMode' => true
						]) ?>
						<?= ButtonControlsWidget::widget([
							'name' => 'filter',
							'items' => [
								'filter-type1' => 'Чаптеры',
								'filter-type2' => 'Кластеры',
								'filter-type3' => 'Команды',
								'filter-type11' => 'Трайбы',
								'filter-type9' => 'Функциональные блоки',
								'filter-type10' => 'Функциональные блоки трайба',
//								'filter-division-level1' => 'Подразделения первого уровня',
//								'filter-division-level2' => 'Подразделения второго уровня',
//								'filter-division-level3' => 'Подразделения третьего уровня',
//								'filter-division-level4' => 'Подразделения четвёртого уровня',
//								'filter-division-level5' => 'Подразделения пятого уровня'
							],
							'selection' => [
								'filter-chapter',
								'filter-cluster',
								'filter-command',
								'filter-tribe',
								'filter-fb',
								'filter-tribe-fb'
							]
						]) ?>
					</div>
				</td>
				<td>

					<div class="panel-control">
						<?= Html::a("Таблица", Url::current(['t' => 1]), [
							'class' => 'btn btn-xs btn-info',
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


