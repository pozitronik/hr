<?php

/**
 * @var View $this
 * @var Groups $model
 * @var ActiveDataProvider $provider
 */
declare(strict_types = 1);

use app\components\pozitronik\helpers\Utils;
use app\modules\groups\GroupsModule;
use app\modules\groups\models\Groups;
use app\modules\groups\widgets\navigation_menu\GroupNavigationMenuWidget;
use kartik\form\ActiveForm;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;

$this->title = "Пользователи в группе {$model->name}";

$this->params['breadcrumbs'][] = GroupsModule::breadcrumbItem('Группы');
$this->params['breadcrumbs'][] = GroupsModule::breadcrumbItem($model->name, ['groups/profile', 'id' => $model->id]);
$this->params['breadcrumbs'][] = $this->title;
$countLabel = (($provider->totalCount > 0)?" (".Utils::pluralForm($provider->totalCount, ['пользователь', 'пользователя', 'пользователей']).")":" (нет пользователей)");
?>

<?php $form = ActiveForm::begin(); ?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="panel-control">
				<?= GroupNavigationMenuWidget::widget([
					'model' => $model
				]) ?>
			</div>
			<h3 class="panel-title"><?= $this->title.' '.$countLabel ?></h3>
		</div>

		<div class="panel-body">
			<div class="row">
				<div class="col-md-12">
					<?= $this->render('grid', [
						'model' => $model,
						'provider' => $provider,
						'showUserSelector' => true,
						'showRolesSelector' => true,
						'showDropColumn' => true,
						'heading' => false
					]) ?>
				</div>
			</div>
		</div>

		<div class="panel-footer">
			<div class="btn-group">
				<?= Html::submitButton($model->isNewRecord?'Сохранить':'Изменить', ['class' => $model->isNewRecord?'btn btn-success':'btn btn-primary']) ?>
				<?php if ($model->isNewRecord): ?>
					<?= Html::input('submit', 'more', 'Сохранить и добавить ещё', ['class' => 'btn btn-primary']) ?>
				<?php endif ?>
			</div>
		</div>
	</div>
<?php ActiveForm::end(); ?>