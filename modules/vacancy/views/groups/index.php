<?php
declare(strict_types = 1);

/**
 * @var View $this
 * @var Groups $group
 * @var ActiveDataProvider $provider
 */

use app\helpers\Utils;
use app\modules\groups\models\Groups;
use app\modules\groups\widgets\navigation_menu\GroupNavigationMenuWidget;
use app\modules\vacancy\VacancyModule;
use yii\data\ActiveDataProvider;
use yii\web\View;

$this->title = "Вакансии в группе {$group->name}";

$this->params['breadcrumbs'][] = VacancyModule::breadcrumbItem('Группы', 'groups/groups');
$this->params['breadcrumbs'][] = VacancyModule::breadcrumbItem($group->name, ['groups/profile', 'id' => $group->id]);
$this->params['breadcrumbs'][] = $this->title;
$countLabel = (($provider->totalCount > 0)?" (".Utils::pluralForm($provider->totalCount, ['вакансия', 'вакансии', 'вакансий']).")":" (нет вакансий)");
?>

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
					'provider' => $provider,
					'heading' => false
				]) ?>
			</div>
		</div>
	</div>
</div>
