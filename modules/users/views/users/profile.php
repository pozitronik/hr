<?php
declare(strict_types = 1);

/**
 * @var Users $model
 * @var View $this
 * @var ActiveDataProvider $dataProvider
 */

use app\modules\dynamic_attributes\models\user_attributes\UserAttributesSearch;
use app\modules\graph\assets\VisjsAsset;
use app\modules\salary\models\references\RefUserPositions;
use app\modules\salary\models\references\RefUserPositionTypes;
use app\modules\users\models\Users;
use app\modules\users\UsersModule;
use app\modules\users\widgets\navigation_menu\UserNavigationMenuWidget;
use app\widgets\badge\BadgeWidget;
use yii\data\ActiveDataProvider;
use yii\web\View;
use yii\helpers\Html;

$this->title = "Профиль пользователя {$model->username}";
$this->params['breadcrumbs'][] = UsersModule::breadcrumbItem('Люди');
$this->params['breadcrumbs'][] = $this->title;
VisjsAsset::register($this);

$this->registerJs("graphControl = new GraphControl(_.$('user-profile-tree-container'), '-1', $model->id); graphControl.network.on('afterDrawing', function() {graphControl.fitAnimated()})", View::POS_END);
$this->registerJs("$('#user-profile-tree-container').css({'position':'relative'}) ", View::POS_END);
?>
<div class="panel panel-default profile-panel">
	<div class="panel-heading">
		<div class="panel-control">
			<?= UserNavigationMenuWidget::widget([
				'model' => $model
			]) ?>
		</div>
		<?= Html::img($model->avatar, ['class' => 'profile-avatar']) ?>
		<h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
	</div>
	<div class="clearfix"></div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-3">
				<label>Должность:</label>
				<?= BadgeWidget::widget([
					'models' => $model->relRefUserPositions,
					'useBadges' => true,
					'attribute' => 'name',
					'unbadgedCount' => 3,
					'itemsSeparator' => false,
					"optionsMap" => RefUserPositions::colorStyleOptions(),
					'linkScheme' => [UsersModule::to(), 'UsersSearch[positions]' => $model->position] + $model->getGroupsScope('UsersSearch[groupId]')
				]) ?>
			</div>
			<div class="col-md-3">
				<label>Профиль:</label>
				<?= BadgeWidget::widget([
					'models' => $model->getRefUserPositionTypes()->all(),/*Именно так, иначе мы напоремся на отсечку атрибутов дистинктом (вспомни, как копали с Ваней)*/
					'useBadges' => true,
					'attribute' => 'name',
					'unbadgedCount' => 3,
					'itemsSeparator' => false,
					"optionsMap" => RefUserPositionTypes::colorStyleOptions(),
					'linkScheme' => [UsersModule::to(), 'UsersSearch[positionType]' => 'id']
				]) ?>
			</div>
			<div class="col-md-3">
				<label>Почта:</label>
				<?= $model->email ?>
			</div>
			<div class="col-md-3"></div>
		</div>
		<div class="row">
			<div class="col-md-8">
				<?= $this->render('profile/groups', [
					'model' => $model,
					'provider' => $dataProvider
				]) ?>
			</div>
			<div class="col-md-4">
				<div id="user-profile-tree-container">
				</div>
			</div>

		</div>
		<div class="row">
			<?= $this->render('profile/attributes', [
				'model' => $model,
				'provider' => (new UserAttributesSearch(['user_id' => $model->id]))->search()
			]) ?>
		</div>
	</div>

</div>