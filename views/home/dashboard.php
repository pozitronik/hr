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
use app\widgets\group_card\GroupCardWidget;
use yii\bootstrap\Button;
use yii\bootstrap\ButtonGroup;
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
						<?= Html::radioList('Сортировка', null, [
							'sort-by-type' => 'По типу',
							'sort-by-count' => 'По сотрудникам',
							'sort-by-vacancy' => 'По вакансиям'
						], [
							'item' => function($index, $label, $name, $checked, $value) {
								return Html::input('radio', $name, $value, ['id' => $value, 'class' => 'hidden']).Html::label($label, $value, ['class' => "button $value"]);

//								return Html::radio($name,
//									$checked,
//									[
//										'label' => $label,
//										'value' => $value,
//										'labelOptions' => ['class' => "btn btn-info $value"]
//									]);

							},
							'class' => 'round-borders btn-group'
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
	<?php foreach ($dataProvider->models  as $group): ?>
		<?= GroupCardWidget::widget(['group' => $group]) ?>
	<?php endforeach; ?>
</div>


