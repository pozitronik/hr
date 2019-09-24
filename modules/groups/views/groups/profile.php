<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Groups $model
 * @var ActiveDataProvider $dataProvider
 */

use app\modules\graph\assets\VisjsAsset;
use app\modules\groups\GroupsModule;
use app\modules\groups\models\Groups;
use app\modules\groups\models\references\RefGroupTypes;
use app\modules\groups\widgets\group_users\GroupUsersWidget;
use app\modules\groups\widgets\navigation_menu\GroupNavigationMenuWidget;
use app\widgets\badge\BadgeWidget;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;

$this->title = $model->isNewRecord?'Добавление группы':"Профиль группы {$model->name}";
$this->params['breadcrumbs'][] = GroupsModule::breadcrumbItem('Группы');
$this->params['breadcrumbs'][] = $this->title;
VisjsAsset::register($this);
$this->registerJs("var graphControl = new GraphControl(_.$('group-profile-tree-container'), {$model->id}, -1, -1, -1); graphControl.autofit = true; graphControl.resizeContainer()", View::POS_END);
//$this->registerJs("$('#group-profile-tree-container').css({'position':'relative'}) ", View::POS_END);
?>

<div class="panel panel-default profile-panel">
	<div class="panel-heading">
		<div class="panel-control">
			<?= GroupNavigationMenuWidget::widget([
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
					'models' => $model->relGroupTypes,
					'useBadges' => true,
					'attribute' => 'name',
					'unbadgedCount' => false,
					'itemsSeparator' => false,
					"optionsMap" => RefGroupTypes::colorStyleOptions(),
					"badgeOptions" => [
						'class' => 'badge group-type-name'
					],
					'linkScheme' => [GroupsModule::to(), 'GroupsSearch[type]' => 'id']
				]) ?>
			</div>
			<div class="col-md-10">
				<?= GroupUsersWidget::widget([
					'group' => $model
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
				<div id="group-profile-tree-container" style="height: 500px">
				</div>
			</div>

		</div>
	</div>


</div>
