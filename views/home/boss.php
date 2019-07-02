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
?>

<div class="panel">
	<div class="panel-heading">
		<div class="panel-control">
		</div>
		<h3 class="panel-title"><?= Html::encode($this->title) ?><?= Html::a("<div class='pull-right'>Таблица</div>", Url::current(['t' => 1])) ?></h3>
	</div>
</div>

<?php foreach ($dataProvider->models as $group): ?>
	<?= GroupCardWidget::widget([
		'group' => $group
	]) ?>

<?php endforeach; ?>

