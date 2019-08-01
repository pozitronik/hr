<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var GroupsSearch $searchModel
 * @var ActiveDataProvider $dataProvider
 */

use app\modules\groups\GroupsModule;
use app\modules\groups\models\GroupsSearch;
use app\widgets\group_card\GroupCardWidget;
use kartik\typeahead\Typeahead;
use yii\bootstrap\Html;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\View;

$this->title = 'Мои группы';
$this->params['breadcrumbs'][] = $this->title;
$dataProvider->pagination = false;
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
<?= Typeahead::widget([
	'name' => 'search',
	'options' => ['placeholder' => 'Поиск в группах'],
	'pluginOptions' => ['highlight' => true],
	'dataset' => [
//						[
//							'local' => ArrayHelper::getColumn($dataProvider->models, 'name'),
//							'limit' => 10
//						],
		[
			'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('name')",
			'display' => 'name',
//						'prefetch' => $baseUrl.'/samples/countries.json',
			'remote' => [
				'url' => GroupsModule::to(['ajax/search']).'?term=%QUERY',
				'wildcard' => '%QUERY'
			]
		]
	]
]) ?>
<?php foreach ($dataProvider->models as $group): ?>
	<?= GroupCardWidget::widget(['group' => $group]) ?>

<?php endforeach; ?>

