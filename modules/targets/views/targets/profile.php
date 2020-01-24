<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Targets $model
 * @var ActiveDataProvider $dataProvider
 */

use app\modules\graph\assets\VisjsAsset;
use app\modules\targets\models\references\RefTargetsTypes;
use app\modules\targets\TargetsModule;
use app\modules\targets\models\Targets;
use app\modules\targets\widgets\navigation_menu\TargetNavigationMenuWidget;
use app\widgets\badge\BadgeWidget;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;

$this->title = $model->isNewRecord?'Добавление группы':"Профиль группы {$model->name}";
$this->params['breadcrumbs'][] = TargetsModule::breadcrumbItem('Группы');
$this->params['breadcrumbs'][] = $this->title;
VisjsAsset::register($this);
$this->registerJs("var graphControl = new GraphControl(_.$('target-profile-tree-container'), {$model->id}, -1, -1, -1); graphControl.physics = false; graphControl.autofit = false; graphControl.resizeContainer(); graphControl.fitAnimated(true);", View::POS_END);
//$this->registerJs("$('#target-profile-tree-container').css({'position':'relative'}) ", View::POS_END);
?>

<div class="panel panel-default profile-panel">
	<div class="panel-heading">
		<div class="panel-control">
			<?= TargetNavigationMenuWidget::widget([
				'model' => $model
			]) ?>
		</div>
		<?= Html::img($model->logo, ['class' => 'profile-avatar']) ?>
		<h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
	</div>
	<div class="clearfix"></div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-2">
				<label>Тип:</label>
				<?= BadgeWidget::widget([
					'models' => $model->relTargetTypes,
					'useBadges' => true,
					'attribute' => 'name',
					'unbadgedCount' => false,
					'itemsSeparator' => false,
					"optionsMap" => RefTargetsTypes::colorStyleOptions(),
					"badgeOptions" => [
						'class' => 'badge target-type-name'
					],
					'linkScheme' => [TargetsModule::to(), 'TargetsSearch[type]' => 'id']
				]) ?>
			</div>

		</div>
		<div class="row">
			<div class="col-md-8">
				<?= $this->render('profile/users', [
					'model' => $model,
					'provider' => $dataProvider
				]) ?>
			</div>
			<div class="col-md-4">
				<div id="target-profile-tree-container" style="height: 500px">
				</div>
			</div>
		</div>
	</div>


</div>
