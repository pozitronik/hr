<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var GroupsSearch $searchModel
 * @var ActiveDataProvider $dataProvider
 * @var string|null $title
 * @var string|null $userLink
 */

use app\assets\IsotopeAsset;
use app\assets\MasonryAsset;
use app\models\user\CurrentUser;
use app\modules\groups\models\GroupsSearch;
use app\modules\groups\models\references\RefGroupTypes;
use app\modules\users\UsersAsset;
use app\widgets\button_controls\ButtonControlsWidget;
use app\modules\groups\widgets\group_card\GroupCardWidget;
use app\components\pozitronik\helpers\ArrayHelper;
use yii\bootstrap\Html;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;

UsersAsset::register($this);
MasonryAsset::register($this);
IsotopeAsset::register($this);

$this->title = $title??'Мои группы';
$this->params['breadcrumbs'][] = $this->title;
$dataProvider->pagination = false;

$this->registerJs("var Controls = new DashboardControl('.grid', '.panel-card'/*, function() {Msnry.layout()}*/)", View::POS_END);
$this->registerJs("Controls.refresh()", View::POS_END);
/*Временный код: генерируем список типов групп у пользюка в скопе*/

$userGroupTypes = [];
$userDashboardFilter = CurrentUser::User()->options->get('dashboardFilter');
$styleOptions = RefGroupTypes::colorStyleOptions();

foreach (RefGroupTypes::getGroupsTypesScope(ArrayHelper::getColumn($dataProvider->models, 'type')) as $key => $value) {
	$userGroupTypes["filter-type{$value['id']}"] = [
		'label' => $value['name'],
		'value' => $key,
		'options' => [
				'data-filter' => $value['id'],
				'checked' => in_array($value['id'], $userDashboardFilter, true)?'checked':false
			] + ArrayHelper::getValue($styleOptions, $value['id'], [])
	];
}

?>

<div class="panel">
	<div class="panel-heading auto-height">
		<div class="row">
			<div class="col-md-3">
				<h3 class="panel-title pull-left">
					<?= null === $userLink?Html::encode($this->title):Html::a($this->title, $userLink) ?>
				</h3>
			</div>
			<div class="col-md-8" style="margin-top:8px">
				<?= ButtonControlsWidget::widget([
					'name' => 'filter',
					'items' => $userGroupTypes,
					'options' => [
						'onChange' => new JsExpression("set_option('dashboardFilter', Controls.filtersValues)")
					]
				]) ?>

			</div>

			<div class="col-md-1">
				<div class="panel-control">
					<?= Html::a("Таблица", Url::current(['t' => 1]), [
						'class' => 'btn btn-xs btn-info'
					]) ?>
				</div>
			</div>
		</div>


	</div>
</div>
<div class="grid">
	<div class="grid-sizer"></div>
	<?php foreach ($dataProvider->models as $group): ?>
		<?= GroupCardWidget::widget(['group' => $group]) ?>
	<?php endforeach; ?>
</div>


