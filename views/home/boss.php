<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var GroupsSearch $searchModel
 * @var ActiveDataProvider $dataProvider
 */

use app\modules\groups\models\GroupsSearch;
use app\widgets\group_card\GroupCardWidget;
use yii\bootstrap\Html;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\View;

$this->title = 'Мои группы';
$this->params['breadcrumbs'][] = $this->title;
$dataProvider->pagination = false;

$this->registerJs("normalize_widths()", View::POS_END);
$this->registerJs("var Msnry = new Masonry('.grid',{columnWidth: '.grid-sizer', itemSelector: '.panel-card', percentPosition: true, fitWidth: true}); ", View::POS_END);
?>

<div class="panel">
	<div class="panel-heading">
		<div class="panel-control">
		</div>
		<h3 class="panel-title">
			<div class='pull-left'>
				<?= Html::encode($this->title) ?>
			</div>
			<?= Html::a("<div class='pull-right'>Таблица</div>", Url::current(['t' => 1])) ?>
		</h3>
	</div>
</div>
<div class="grid">
	<div class="grid-sizer"></div>
	<?php foreach ($dataProvider->models as $group): ?>
		<?= GroupCardWidget::widget(['group' => $group]) ?>
	<?php endforeach; ?>
</div>


