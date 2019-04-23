<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Groups $group
 * @var ActiveDataProvider $provider
 */

use app\helpers\Utils;
use app\models\core\core_module\CoreModule;
use app\modules\groups\models\Groups;
use app\modules\groups\widgets\navigation_menu\GroupNavigationMenuWidget;
use kartik\form\ActiveForm;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\View;

$this->title = "Вакансии в группе {$group->name}";

$this->params['breadcrumbs'][] = CoreModule::breadcrumbItem('Группы');
$this->params['breadcrumbs'][] = CoreModule::breadcrumbItem($group->name, 'groups/profile', ['id' => $group->id]);
$this->params['breadcrumbs'][] = $this->title;
$countLabel = (($provider->totalCount > 0)?" (".Utils::pluralForm($provider->totalCount, ['вакансия', 'вакансии', 'вакансий']).")":" (нет вакансий)");
?>

<?php $form = ActiveForm::begin(); ?>
<div class="panel panel-default">
	<div class="panel-heading">
		<div class="panel-control">
			<?= GroupNavigationMenuWidget::widget([
				'model' => $group
			]) ?>
		</div>
		<h3 class="panel-title"><?= $this->title.' '.$countLabel ?></h3>
	</div>

	<div class="panel-body">
		<div class="row">
			<div class="col-md-12">
				<?= $this->render('grid', [
					'model' => $group,
					'provider' => $provider,
					'showRolesSelector' => true,
					'showDropColumn' => true,
					'heading' => false
				]) ?>
			</div>
		</div>
	</div>

	<div class="panel-footer">
		<div class="btn-group">
			<?= Html::submitButton($group->isNewRecord?'Сохранить':'Изменить', ['class' => $group->isNewRecord?'btn btn-success':'btn btn-primary']) ?>
			<?php if ($group->isNewRecord): ?>
				<?= Html::input('submit', 'more', 'Сохранить и добавить ещё', ['class' => 'btn btn-primary']) ?>
			<?php endif ?>
		</div>
	</div>
</div>
<?php ActiveForm::end(); ?>
