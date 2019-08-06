<?php
declare(strict_types = 1);

/**
 * @var Users $model
 * @var View $this
 * @var ActiveDataProvider $dataProvider
 */

use app\modules\dynamic_attributes\models\user_attributes\UserAttributesSearch;
use app\modules\references\ReferencesModule;
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

?>
<div class="panel panel-default profile-panel">
	<div class="panel-heading">
		<div class="panel-control">
			<?= UserNavigationMenuWidget::widget([
				'model' => $model
			]) ?>
		</div>
		<?= Html::img($model->avatar, ['class' => 'profile-avatar']); ?>
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
					"optionsMap" => static function() {
						return RefUserPositionTypes::colorStyleOptions();
					},
					'linkScheme' => [ReferencesModule::to(['references/update']), 'id' => 'id', 'class' => 'RefUserPositions']
				]); ?>
			</div>
			<div class="col-md-3">
				<label>Профиль:</label>
				<?= BadgeWidget::widget([
					'models' => $model->getRefUserPositionTypes()->all(),/*Именно так, иначе мы напоремся на отсечку атрибутов дистинктом (вспомни, как копали с Ваней)*/
					'useBadges' => true,
					'attribute' => 'name',
					'unbadgedCount' => 3,
					'itemsSeparator' => false,
					"optionsMap" => static function() {
						return RefUserPositionTypes::colorStyleOptions();
					}
				]); ?>
			</div>
			<div class="col-md-3">
				<label>Почта:</label>
				<?= $model->email ?>
			</div>
			<div class="col-md-3"></div>
		</div>
		<div class="row">
			<?= $this->render('profile/groups',[
				'model' => $model,
				'provider' => $dataProvider
			]) ?>
		</div>
		<div class="row">
			<?= $this->render('profile/attributes',[
				'model' => $model,
				'provider' => (new UserAttributesSearch(['user_id' => $model->id]))->search()
			]) ?>
		</div>
	</div>

	<div class="panel-footer">
	</div>
</div>